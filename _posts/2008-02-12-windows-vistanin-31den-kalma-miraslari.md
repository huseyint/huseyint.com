---
title: 'Windows Vista&#8217;nın 3.1&#8242;den kalma mirasları'
author: Hüseyin Tüfekçilerli
layout: post
permalink: /2008/02/windows-vistanin-31den-kalma-miraslari/
comments: true
categories:
  - Genel
tags:
  - 3.1
  - dialog
  - screenshot
  - vista
  - windows
---
Dün akşam Windows Vista yüklü bilgisayarıma bir Font yüklemeye çalıştığımda aşağıdaki dialog karşıma çıktı: 

<p align="center">
  <img src="http://huseyint.com/wp-content/uploads/2008/02/add-fonts.png" alt="" />
</p>

Gelmiş geçmiş Windows&#8217;ların içinde ([Millenium Edition][1] da dahil) en fiyakalısı olan Vista&#8217;da hala bu dialog neden bulunuyor? Atlamışlar heralde&#8230; Neyse bunu gördükten sonra bir başka tarihi eser olan moricons.dll de hala yerinde mi diye baktım. Yanlış hatırlamıyorsam C:\Windows klasöründe olması gerekiyordu ama yoktu. Klasör içinde arama yapınca System32 klasöründe olduğunu gördüm: 

<p align="center">
  <img src="http://huseyint.com/wp-content/uploads/2008/02/change-icon.png" alt="" />
</p>

Eğer moricons.dll içindeki 32&#215;32 büyüklüğünde ve tamı tamına 16 renk derinliğine sahip simgelere bağımlılığı bulunan uygulamalarınız varsa korkmayın, Windows Vista altında da uygulamalarınız sorunsuz bir şekilde çalışacaktır (<a href="http://en.wikipedia.org/wiki/User_Account_Control" class="ubernym uttInitialism"><acronym class="uttInitialism" title="User Account Control">UAC</acronym></a>&#8217;yi saymazsak). [Backward compatibility][2] bu olsa gerek.

 [1]: http://en.wikipedia.org/wiki/Windows_Me
 [2]: http://en.wikipedia.org/wiki/Backward_compatibility