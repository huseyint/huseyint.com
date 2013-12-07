---
title: Calling an overridden method on a JavaScript object
author: Hüseyin Tüfekçilerli
date: 2013-12-07
layout: post
permalink: /2013/12/Calling-an-overridden-function-on-a-JavaScript-object/
comments: true
categories:
  - JavaScript
tags:
  - javascript
---
Suppose you have the following form on your HTML page:

```html
<form id="MyForm" action="process.aspx" method="POST">
  <input type="text" name="foo" />
  <input type="submit" name="submit" value="Send" />
</form>
```
Seems pretty innocent right? No. At least until you try to submit that form using JavaScript code. Try running the following JavaScript code on a page that contains this form inside developer tools console of your favorite browser:

```javascript
document.getElementById('MyForm').submit()
```

You will get a lovely error, on Chrome:

```
TypeError: Property 'submit' of object #<HTMLFormElement> is not a function
```

On Internet Explorer:

```
Member not found.
```

But how come? Is [```submit()```](https://developer.mozilla.org/en-US/docs/Web/API/HTMLFormElement.submit) broken? Chrome's error message hints that a property named ```submit``` exists but not a function. Let's see what it is:

```javascript
document.getElementById('MyForm').submit
```

This call will return a reference to submit button element in the form. Apparently you can access child form input elements as properties of parent form element, neat! But seriously, I need to call the ```submit()``` method. To do that I need to have a reference to the overridden function. If I had a reference to that function I know I can call it with the form reference I have in hand, thanks to [```call()```](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Function/call) method exists on all JavaScript functions. Using the ```call()``` method I can execute the ```submit()``` function with any instance I want. OK then, how about using another form reference's non-overridden ```submit()``` method, let's try this:

```javascript
document.createElement('form').submit(document.getElementById('MyForm'))
```

Bingo! It worked. But it doesn't feel quite right, especially creating another form element to solely access one of its methods? There should be a better way. A little research took me to this: [```HTMLFormElement```](https://developer.mozilla.org/en/docs/Web/API/HTMLFormElement) which represents the base of all form elements and has references to all form functions accessible through its ```prototype```, great! Let's give the following revised code a chance:

```javascript
HTMLFormElement.prototype.submit.call(document.getElementById('MyForm'))
```

This worked and is a reasonable solution for this problem.