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

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #6666cc; font-weight: bold;">string</span> a <span style="color: #008000;">=</span> <span style="color: #0600FF; font-weight: bold;">null</span><span style="color: #008000;">;</span>
<span style="color: #6666cc; font-weight: bold;">string</span> b <span style="color: #008000;">=</span> <span style="color: #666666;">"foo"</span><span style="color: #008000;">;</span>
<span style="color: #6666cc; font-weight: bold;">string</span> c <span style="color: #008000;">=</span> a <span style="color: #008000;">??</span> b<span style="color: #008000;">;</span></pre>
      </td>
    </tr>
  </table>
</div>

kodu çalıştırıldığında c değişkeni a null değerine sahip olduğu için b&#8217;nin değeri olan &#8220;foo&#8221; değerine sahip oluyor. İşimizi bir çok sefer kolaylaştıran diğer bir operatör **?:** [Ternary operatöründen][1] bile daha okunabilir bir kod sağlıyor. Ternary ile bunu şu şekilde yazabilirdik:

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #6666cc; font-weight: bold;">string</span> a <span style="color: #008000;">=</span> <span style="color: #0600FF; font-weight: bold;">null</span><span style="color: #008000;">;</span>
<span style="color: #6666cc; font-weight: bold;">string</span> b <span style="color: #008000;">=</span> <span style="color: #666666;">"foo"</span><span style="color: #008000;">;</span>
<span style="color: #6666cc; font-weight: bold;">string</span> c <span style="color: #008000;">=</span> a <span style="color: #008000;">!=</span> <span style="color: #0600FF; font-weight: bold;">null</span> <span style="color: #008000;">?</span> a <span style="color: #008000;">:</span> b<span style="color: #008000;">;</span></pre>
      </td>
    </tr>
  </table>
</div>

Hele bir de if kullansaydık bu işlem için bu kadar daha kod yazmamız gerekecekti:

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #6666cc; font-weight: bold;">string</span> a <span style="color: #008000;">=</span> <span style="color: #0600FF; font-weight: bold;">null</span><span style="color: #008000;">;</span>
<span style="color: #6666cc; font-weight: bold;">string</span> b <span style="color: #008000;">=</span> <span style="color: #666666;">"foo"</span><span style="color: #008000;">;</span>
&nbsp;
<span style="color: #0600FF; font-weight: bold;">if</span> <span style="color: #008000;">&#40;</span>a <span style="color: #008000;">!=</span> <span style="color: #0600FF; font-weight: bold;">null</span><span style="color: #008000;">&#41;</span>
    c <span style="color: #008000;">=</span> a<span style="color: #008000;">;</span>
<span style="color: #0600FF; font-weight: bold;">else</span>
    c <span style="color: #008000;">=</span> b<span style="color: #008000;">;</span></pre>
      </td>
    </tr>
  </table>
</div>

Operatörü iç içe geçmiş bir şekilde kullanırsak bir kaç değişken arasından ilk null olmayanını bulabiliriz:

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #6666cc; font-weight: bold;">string</span> a <span style="color: #008000;">=</span> <span style="color: #0600FF; font-weight: bold;">null</span><span style="color: #008000;">;</span>
<span style="color: #6666cc; font-weight: bold;">string</span> b <span style="color: #008000;">=</span> <span style="color: #0600FF; font-weight: bold;">null</span><span style="color: #008000;">;</span>
<span style="color: #6666cc; font-weight: bold;">string</span> c <span style="color: #008000;">=</span> <span style="color: #666666;">"foo"</span><span style="color: #008000;">;</span>
<span style="color: #6666cc; font-weight: bold;">string</span> d <span style="color: #008000;">=</span> a <span style="color: #008000;">??</span> b <span style="color: #008000;">??</span> c<span style="color: #008000;">;</span> <span style="color: #008080; font-style: italic;">// d = "foo"</span></pre>
      </td>
    </tr>
  </table>
</div>

Operatörün MSDN sayfasına [şuradan][2] ulaşabilirsiniz.

Bu operatör aslında JavaScript yazarken kullandığımız **||** operatörü ile aynı işi yapıyor; ilk operand null değilse onu null ise sonrakini döndür.

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="javascript" style="font-family:monospace;"><span style="color: #000066; font-weight: bold;">var</span> foo <span style="color: #339933;">=</span> <span style="color: #3366CC;">"bar"</span><span style="color: #339933;">;</span>
<span style="color: #000066; font-weight: bold;">var</span> baz <span style="color: #339933;">=</span> <span style="color: #003366; font-weight: bold;">null</span><span style="color: #339933;">;</span>
alert<span style="color: #009900;">&#40;</span>foo <span style="color: #339933;">||</span> baz<span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span></pre>
      </td>
    </tr>
  </table>
</div>

 [1]: http://msdn2.microsoft.com/en-us/library/ty67wk28(VS.80).aspx
 [2]: http://msdn2.microsoft.com/en-us/library/ms173224(VS.80).aspx