---
title: How to convert a .NET string to a Stream
author: Hüseyin Tüfekçilerli
date: 2013-12-29
layout: post
permalink: /2013/12/How-to-convert-a-.NET-string-to-a-Stream/
comments: true
categories:
  - .NET
tags:
  - csharp
  - string
  - stream
  - utf8
  - unicode
---
If you ask this question on Google, you will find the following code snippet all over the web:

```csharp
var text = "lorem ipsum dolor sit amet";
var bytes = Encoding.UTF8.GetBytes(text);
var stream = new MemoryStream(bytes);
```

This totally answers the question and works fine, but you should weigh your requirements while using this code. With short strings, that shouldn't be much problem but if you are working with larger ones, the call to ```GetBytes()``` method will allocate a huge array at least the length of your string. And worse, if this byte array is larger than 85.000 bytes, it will go into the [Large Object Heap (LOH)](http://msdn.microsoft.com/en-us/magazine/cc534993.aspx) which we should avoid as much as we can. Think also about this, if you need to pass that string to a consumer which expects a ```Stream```, this should hint that this consumer won't need that bulk of bytes at a single point of time, rather it will read those bytes batch by batch using regular streaming interface techniques.

[Stephen Toub](http://blogs.msdn.com/b/toub/), on [one of his MSDN Magazine blog posts](http://msdn.microsoft.com/en-us/magazine/cc163768.aspx) writes about this very issue and comes up with 3 solutions. The first two are very much like the code snippet above, only the second one is a bit refactored version of it but it has still the same semantics.

On the other hand, the third solution illustrates a quite nice approach as you can see on *Figure 1 Alternate StringStream Implementation* code listing. Possibly due to recent style changes on MSDN pages, the code is quite garbled but you can copy it over to Visual Studio and auto format it. This is a regular .NET ```Stream``` implementation code with the required overridden implementations. The interesting part is the ```Read()``` method which performs the real work:

```csharp
public override int Read(byte[] buffer, int offset, int count)
{
  if (_position < 0) throw new InvalidOperationException();

  int bytesRead = 0;

  while (bytesRead < count)
  {
	if (_position >= _byteLength) return bytesRead;
	char c = _str[_position / 2];
	byte b = (byte)(_position % 2 == 0 ? c & 0xFF : (c >> 8) & 0xFF);
	buffer[offset + bytesRead] = b;
	Position++;
	bytesRead++;
  }

  return bytesRead;
}
```

This method, on each call, tries to fill the given ```buffer``` with the byte representations of the remaining string until either the buffer is full or it reaches to the end of the underlying string. Since .NET strings are UTF-16 encoded, each char is represented with 2 bytes. The ```while``` loop in this method on each iteration writes a single byte, hence writing out a single char takes 2 iterations, one for lower byte and the other for upper byte. You can see this ```StringStream``` implementation in action with a code like this:

```csharp
var text = "lorem ipsum dolor sit amet";

using (var stream = new StringStream(text))
using (var reader = new StreamReader(stream, Encoding.Unicode))
{
  var read = reader.ReadToEnd();

  Assert.AreEqual(text, read);
}
```

Here I am providing a ```text``` to ```StringStream``` constructor, giving that stream to a ```StreamReader```, reading to the end of the stream and what I ```read``` back is the exact same string I have provided in the first place. But notice that the second parameter of ```StreamReader``` constructor which is ```Encoding.Unicode```, that is required in this case because ```StreamReader``` defaults to ```Encoding.UTF8``` in the absence of an ```encoding``` parameter. We need to explicitly specify the encoding because the underlying stream implementation will spit the bytes according to Unicode (UTF-16) encoding rules.

If the consumer of your stream instance doesn't use Unicode encoding while decoding the bytes that come out of your ```StringStream.Read()``` method, it will fail to get the desired string decoded. In the previous code snippet, go either delete the second parameter of ```StreamReader``` constructor or replace it with ```Encoding.UTF8```, you will ```read``` a string like this:

```
"l\0o\0r\0e\0m\0 \0i\0p\0s\0u\0m\0 \0d\0o\0l\0o\0r\0 \0s\0i\0t\0 \0a\0m\0e\0t\0"
```

This string has a null char for every other char, this is because UTF-8 represents the characters in English alphabet (or any character in ASCII subset for that matter) with a single byte and secondary ```0x00``` bytes are represented as null chars in this string. The encoding assumption of ```StringStream``` class fails the consumer of this stream. To fix this issue, the ```StringStream``` class must know the desired encoding to use when spitting out bytes. I have modified this class to accept an encoding in its constructor and produce bytes according to the specified encoding rules. Here is what ```Read()``` method became with this implementation:

```csharp
public override int Read(byte[] buffer, int offset, int count)
{
  if (_position < 0)
  {
    throw new InvalidOperationException();
  }

  var bytesRead = 0;
  var chars = new char[1];

  // Loop until the buffer is full or the string has no more chars
  while (bytesRead < count && _position < _str.Length)
  {
    // Get the current char to encode
    chars[0] = _str[_position];

    // Get the required byte count for current char
    var byteCount = _encoding.GetByteCount(chars);

    // If adding current char to buffer will exceed its length, do not add it
    if (bytesRead + byteCount > count)
    {
      return bytesRead;
    }

    // Add the bytes of current char to byte buffer at next index
    _encoding.GetBytes(chars, 0, 1, buffer, offset + bytesRead);

    // Increment the string position and total bytes read so far
    Position++;
    bytesRead += byteCount;
  }

  return bytesRead;
}
```

The key point in this implementation is that we are first making sure there is enough room to put the byte(s) of current char to ```buffer```, to do that we are using [```Encoding.GetByteCount()```](http://msdn.microsoft.com/en-us/library/z2s2h516.aspx) method. If there are no rooms left to encode current char, the method simply returns with the total bytes read count so far, leaving the rest of reading to next ```Read()``` method call. If there is room, then the method uses [```Encoding.GetBytes()```](http://msdn.microsoft.com/en-us/library/ms149355.aspx) method to write current char byte(s) to buffer at next index.

You can find the [source code of this class at my GitHub repository](https://github.com/huseyint/StringStream).