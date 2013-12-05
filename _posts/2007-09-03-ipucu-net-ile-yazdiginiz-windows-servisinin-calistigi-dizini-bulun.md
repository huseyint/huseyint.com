---
title: 'İpucu: .NET ile yazdığınız Windows servisinin çalıştığı dizini bulun'
author: Hüseyin Tüfekçilerli
layout: post
permalink: /2007/09/ipucu-net-ile-yazdiginiz-windows-servisinin-calistigi-dizini-bulun/
comments: true
categories:
  - .NET
  - İpucu
---
Bir Windows Forms uygulamasında `Application.StartupPath` ile eriştiğimiz çalışma dizinine Windows servisi şartları altında (`System.Windows.Forms.Application` sınıfına ulaşamadığımız bir durumda) şu şekilde erişebiliriz:

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="vbnet" style="font-family:monospace;">System<span style="color: #000000;">.</span><span style="color: #000000;">IO</span><span style="color: #000000;">.</span><span style="color: #000000;">Path</span><span style="color: #000000;">.</span><span style="color: #000000;">GetDirectoryName</span><span style="color: #000000;">&#40;</span>System<span style="color: #000000;">.</span><span style="color: #000000;">Reflection</span><span style="color: #000000;">.</span><span style="color: #000000;">Assembly</span><span style="color: #000000;">.</span><span style="color: #000000;">GetExecutingAssembly</span><span style="color: #000000;">&#40;</span><span style="color: #000000;">&#41;</span><span style="color: #000000;">.</span><span style="color: #000000;">Location</span><span style="color: #000000;">&#41;</span></pre>
      </td>
    </tr>
  </table>
</div>