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

```csharp
System.IO.Path.GetDirectoryName(System.Reflection.Assembly.GetExecutingAssembly().Location)
```