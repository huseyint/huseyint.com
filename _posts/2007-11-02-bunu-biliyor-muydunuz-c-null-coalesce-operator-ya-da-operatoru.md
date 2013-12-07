---
title: 'Bunu biliyor muydunuz: C# Null Coalesce Operator ya da ?? operatörü'
author: Hüseyin Tüfekçilerli
layout: post
permalink: /2007/11/bunu-biliyor-muydunuz-c-null-coalesce-operator-ya-da-operatoru/
comments: true
categories:
  - .NET
  - İpucu
---
Geçenlerde bir videoda **??** şeklinde bir C# operatörünün kullanımına şahit oldum. Başta .NET 3.5 ile gelen yeni özelliklerden biri gibi gelse de bu operatör aslında .NET 2.0&#8242;dan beri varmış da haberimiz yokmuş. Videodaki kodun gelişinden operatörün ne amaçla kullanıldığı açıktı; operatörün solunda bulunan operandın (operandın Türkçe&#8217;si **işlenen**miş bu arada) değeri null değilse işlem sonucu bu (soldaki) operand, null ise de sağdaki operand dönüyor. Şu şekilde:

```csharp
string a = null;
string b = "foo";
string c = a ?? b;
```

kodu çalıştırıldığında c değişkeni a null değerine sahip olduğu için b&#8217;nin değeri olan &#8220;foo&#8221; değerine sahip oluyor. İşimizi bir çok sefer kolaylaştıran diğer bir operatör **?:** [Ternary operatöründen][1] bile daha okunabilir bir kod sağlıyor. Ternary ile bunu şu şekilde yazabilirdik:

```csharp
string a = null;
string b = "foo";
string c = a != null ? a : b;
```

Hele bir de if kullansaydık bu işlem için bu kadar daha kod yazmamız gerekecekti:

```csharp
string a = null;
string b = "foo";
 
if (a != null)
    c = a;
else
    c = b;
```

Operatörü iç içe geçmiş bir şekilde kullanırsak bir kaç değişken arasından ilk null olmayanını bulabiliriz:

```csharp
string a = null;
string b = null;
string c = "foo";
string d = a ?? b ?? c; // d = "foo"
```

Operatörün MSDN sayfasına [şuradan][2] ulaşabilirsiniz.

Bu operatör aslında JavaScript yazarken kullandığımız **||** operatörü ile aynı işi yapıyor; ilk operand null değilse onu null ise sonrakini döndür.

```javascript
var foo = "bar";
var baz = null;
alert(foo || baz);
```

 [1]: http://msdn2.microsoft.com/en-us/library/ty67wk28(VS.80).aspx
 [2]: http://msdn2.microsoft.com/en-us/library/ms173224(VS.80).aspx