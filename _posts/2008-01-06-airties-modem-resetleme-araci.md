---
title: AirTies modem resetleme aracı
author: Hüseyin Tüfekçilerli
layout: post
permalink: /2008/01/airties-modem-resetleme-araci/
comments: true
categories:
  - Genel
tags:
  - adsl
  - airties
  - modem
  - rapidshare
  - reset
---
<p><a href="http://www.rapidshare.com/">RapidShare</a> gibi sitelerden Premium hesap sahibi olmadan ücretsiz olarak dosya indiriyorsanız daha kısa zamanda daha fazla dosya indirebilmek için sık sık modeminize reset atmak zorunda kalıyorsunuzdur. Böylelikle modeminiz yeni bir IP alıyor ve sanki bambaşka biriymişçesine RapidShare&#8217;den dosyalarınızı indirmeye devam edebiliyorsunuz. Bir programcı olduğumu ve programcıların da tembel olduğunu göz önüne aldığımızda modemin elektrik kablosunu çıkarmak ve tekrar takmak oldukça zahmetli bir iş benim için. İşte bu yüzden bu sorunu tek tıkla çözen bir <a href="http://en.wikipedia.org/wiki/Command_line_interface" title="CLI - Command line interface">komut satırı uygulaması</a> geliştirdim. Eğer sizin de AirTies marka bir ADSL modeminiz varsa bu aracı kullanarak modeminize reset atabilir, yeni bir IP adresi alabilirsiniz. Modem arayüzüne 192.168.2.1 adresinden ve boş bir şifre ile ulaştığınızı varsayarsak programı hiçbir parametre göndermeden, çift tıklayarak, kullanabilirsiniz. Ancak modem arayüzüne eriştiğiniz IP adresiniz farklı ve/veya modeme erişmede bir şifre kullanıyorsanız ilgili <strong>/ip</strong> ve <strong>/password</strong> parametlerini kullanmanız gerekir. Programı çalıştırabilmeniz için <a href="http://go.microsoft.com/fwlink/?LinkId=37283">.NET Framework 2.0</a> veya üstü bilgisayarınızda yüklü olduğundan emin olun.</p>
<p><a href='http://huseyint.com/wp-content/uploads/2008/01/resetmodem.png' title='AirTies modem resetleme aracı ekran görüntüsü'><img src='http://huseyint.com/wp-content/uploads/2008/01/resetmodem.thumbnail.png' alt='AirTies modem resetleme aracı ekran görüntüsü' /></a></p>
<p><a href="/projeler/ResetModem/ResetModem.zip" title="AirTies ADSL modem resetleme aracını indirin">AirTies ADSL Modem Reset Utility 1.0.0.0</a> (3,40 KB)</p>
<p><strong>Not:</strong> Programı sadece kullanmakta olduğum AirTies RT-205 modeli ile test edebildim ancak diğer modeller ile de sorunsuz çalışacağını ümit ediyorum.</p>
<p><strong>Güncelleme:</strong> Programın <a href="/projeler/ResetModem/Program.cs.html" title="AirTies ADSL modem resetleme aracı kaynak kodu">C# kaynak kodunu</a> görebilirsiniz.</p>