---
title: 'Metin kutularına ipucu metni koyma &#8211; Cue Banner Text'
author: Hüseyin Tüfekçilerli
layout: post
permalink: /2008/01/metin-kutularina-ipucu-metni-koyma-cue-banner-text/
comments: true
categories:
  - .NET
tags:
  - .NET
  - 'c#'
  - cue banner
  - textbox
  - windows forms
---
Gerek Windows Forms ile gerekse de Web Forms ile bir kullanıcı arayüzü tasarlarken sıkça Label ve TextBox kontrol çiftlerini kullanırız. Bir TextBox koyarız ki kullanıcıdan bir değer alabilelim, soluna da bir Label koyarız ki kullanıcıdan ne bilgisi istediğimiz belli olsun. Ancak bazen ya yer darlığından ya da estetik kaygıdan dolayı TextBox kontrolünün işlevini belirleyen Label kontrolü gözümüze batar, kaldırmak isteriz. Böylelikle Label kontrolünden kurtuluruz kurtulmasına da bir şekilde kullanıcıya TextBox kontrolüne ne gireceği hakkında bilgi vermemiz gerekir. İşte bu noktada Cue Banner Text kullanmamız gereken fonksiyonalitedir. Peki nedir bu Cue Banner Text? Hani bazı web sitelerinin Login bölümlerinde kullanıcı adı ve parola gireceğiniz metin kutularının üstüne gelince **Kullanıcı Adı** ve **Parola** yazıları kayboluverir ki siz kendi kullanıcı adı ve parola değerlerini girebilirsiniz. İşte metin kutularının içine o kutulara ne girileceğini yazmak ve kutuya tıklayınca (veya bir şeyler yazmaya başlayınca) bu yazılara yok etme yöntemine Cue Banner Text deniyor. Eğer web ortamında bu yöntemi kullanarak erişilebilir formlar oluşturmak istiyorsanız A List Apart&#8217;taki [Making Compact Forms More Accessible][1] makalesini okuyarabilirsiniz. 

<p align="center">
  <img src="http://huseyint.com/wp-content/uploads/2008/01/sample_form.gif" alt="Cue Banner Text yöntemi uygulanmış örnek bir web formu" />
</p>

Masaüstü uygulamalarında da bu tarz TextBox kullanımını görmemiz münkün. Mesela Windows Vista&#8217;da her bir Explorer penceresinin sağ üst köşesinde ve Internet Explorer 7&#8242;de bulunan arama kutularında bu kullanım mevcut: 

<p align="center">
  <img src="http://huseyint.com/wp-content/uploads/2008/01/vistaexplorersearchbox.png" alt="Windows Vista Explorer arama kutusu" /><br /> <img src="http://huseyint.com/wp-content/uploads/2008/01/ie7searchbox.png" alt="IE7 arama kutusu" />
</p>

Yine Windows Vista&#8217;da başlat menüsünün alt kısmında çıkan arama kutusunda da bunu görmekteyiz: 

<p align="center">
  <img src="http://huseyint.com/wp-content/uploads/2008/01/vistastartmenusearchbox.png" alt="Windows Vista başlat menüsü arama kutusu" />
</p>

Sizin de bu yöntemi .NET ve Windows Forms kullanarak geliştirdiğiniz uygulamalarda kullanmanız mümkün. Ne yazık ki .NET ile gelen TextBox kontrolünün böyle bir özelliği yok, bir şekilde bunu kendimiz yapmamız gerek. İlk akla gelen yöntemlerden biri kullandığımız TextBox kontrolünün Enter ve Leave eventlerini yakalayıp yazıyı gösterme/göstermeme işlemini gerçekleştirebilirsiniz. Benzer bir yöntemle ama bu sefer biraz daha alt seviyede TextBox kontrolünü subclass ederek yeni bir kontrol oluşturabilir ve OnGotFocus/OnLostFocus metodlarını override ederek benzer işlevi yeni kontrole kazandırmanız da mümkün. Ama tahmin edebileceğiniz gibi bu yöntemler pek de temiz gibi görünmüyor. Hem yazacağımız kod gereğinden fazla karışık ve yönetilemez olacak, hem de kontrolün temiz/sorunsuz bir kullanımı olmayacak. Geçiniz&#8230; 

Peki nasıl yapalım biz bu işi? Şöyle oluyor efendim; [EM_SETCUEBANNER][2] mesajını TextBox kontrolümüze User32.dll kütüphanesindeki meşhur [SendMessage][3] fonksiyonu ile göndererek. Aynen yukarıda örnek verdiğim Windows uygulamalarının da yaptığı gibi. Eğer programınız Windows XP veya sonrası bir işletim sisteminde çalışıyorsa bu yöntemi kullanarak acısız ve ağrısız bir şekilde TextBox kontrollerinize Cue Banner Text özelliği kazandırabilirsiniz. 

SendMessage User32 sistem kütüphanesinde export edilmiş bir fonksiyon olduğu için bu fonksiyonu .NET içerisinden Platform Invocation veya kısaltacak olursak [P/Invoke][4] ile çağırmamız mümkün. EM_SETCUEBANNER sabitinin değeri ve SendMessage fonksiyonunun kullanacağımız versiyonu şu şekilde: 

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #0600FF; font-weight: bold;">internal</span> <span style="color: #0600FF; font-weight: bold;">const</span> <span style="color: #6666cc; font-weight: bold;">uint</span> ECM_FIRST <span style="color: #008000;">=</span> 0x1500<span style="color: #008000;">;</span>
<span style="color: #0600FF; font-weight: bold;">internal</span> <span style="color: #0600FF; font-weight: bold;">const</span> <span style="color: #6666cc; font-weight: bold;">uint</span> EM_SETCUEBANNER <span style="color: #008000;">=</span> ECM_FIRST <span style="color: #008000;">+</span> <span style="color: #FF0000;">1</span><span style="color: #008000;">;</span>
&nbsp;
<span style="color: #008000;">&#91;</span>DllImport<span style="color: #008000;">&#40;</span><span style="color: #666666;">"user32.dll"</span>, CharSet <span style="color: #008000;">=</span> CharSet<span style="color: #008000;">.</span><span style="color: #0000FF;">Auto</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">&#93;</span>
<span style="color: #0600FF; font-weight: bold;">internal</span> <span style="color: #0600FF; font-weight: bold;">static</span> <span style="color: #0600FF; font-weight: bold;">extern</span> IntPtr SendMessage<span style="color: #008000;">&#40;</span>
    HandleRef hWnd, 
    <span style="color: #6666cc; font-weight: bold;">uint</span> msg, 
    <span style="color: #6666cc; font-weight: bold;">bool</span> wParam, 
    <span style="color: #6666cc; font-weight: bold;">string</span> lParam<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span></pre>
      </td>
    </tr>
  </table>
</div>

Fonksiyonun parametrelerinin ne işe yaradığınız özetlemek gerekirse; [HandleRef][5] tipindeki hWnd parametresi ile TextBox kontrolümüzün Handle değerini, msg parametresi ile EM\_SETCUEBANNER sabitini, wParam ile Cue Banner metninin kontrol üzerinde iken de gösterilip gösterilmeyeceğini ve son olarak lParam ile de Cue Banner metnini göndereceğiz. Burada 3. parametre olan wParam için özel bir durum var. Windows Vista&#8217;ya kadar EM\_SETCUEBANNER mesajını gönderirken bu parametre göz ardı ediliyordu. Ancak Vista ile beraber buraya true değeri göndermemiz durumunda Cue Banner metni kontrol üzerinde iken de görünüyor, ta ki kullanıcı ilk karakteri girene kadar. Eğer Windows Vista kullanıyorsanız bu özelliği başlat menüsündeki arama kutusunda deneyebilirsiniz. Aşağıdaki kod txtSearch isminde bir TextBox kontrolüne Cue Banner olarak &#8220;Search&#8221; metnini atıyor: 

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;">SendMessage<span style="color: #008000;">&#40;</span>
    <span style="color: #008000;">new</span> HandleRef<span style="color: #008000;">&#40;</span>txtSearch, txtSearch<span style="color: #008000;">.</span><span style="color: #0000FF;">Handle</span><span style="color: #008000;">&#41;</span>, 
    EM_SETCUEBANNER, 
    <span style="color: #0600FF; font-weight: bold;">false</span>, 
    <span style="color: #666666;">"Search"</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span></pre>
      </td>
    </tr>
  </table>
</div>

Kodu bu haliyle Windows Formunuzun constructoruna veya Form_Load eventine yerleştirmeniz mümkün. Ama bir kaç satır kod yazarak TextBox kontrolünden türeyen ve Cue Banner Text özelliği olan yeni bir TextBox kontrolü oluşturabiliriz. Böylelikle projenizin farklı yerlerinde böyle bir kontrol kullanacak olursanız sanki formunuza normal bir TextBox kontrolü yerleştirirmiş gibi bu kontrolü yerleştirip Properties penceresinden de Cue Banner metnini belirleyebilirsiniz. 

<p align="center">
  <img src='http://huseyint.com/wp-content/uploads/2008/01/cuebannertextboxdemo.png' alt='CueBannerTextBox kontrol demosu' />
</p>

Fark ettiyseniz Windows Explorer, IE7 ve başlat menüsünde bulunan arama kutularındaki Cue Banner metinleri gri renkte ve italik yazı tipi ile yazılmış, bizim oluşturduğumuz ise gri olmasına rağmen italik değil. Channel 9 Forumlarında [littleguru][6] adındaki kullanıcının daha önceden [oluşturmuş olduğu][7] benzer bir kontrolde ek olarak Cue Banner fontunu belirleme seçeneği de koymuş. OnGotFocus/OnLostFocus metodlarını override ederek TextBox&#8217;un o anki font değerini değiştiriyor. Ancak program çalıştığı esnada bu değerlerin (Cue Banner metni ve fontu) değişmesi gibi bazı durumunda kontrol pek sağlıklı çalışmıyor. Ayrıca bahsettiğim Vista ile gelen yeni özellik de o kontrolde uygulanmış değil. O yüzden bu işi yapan kontrolü tekrar yazmaya karar verdim. Tekrar yazmak dediysem pek de bir şey yapmadım hani, littleguru&#8217;nun kodunu baz alarak bir kaç değişiklik yaptım. Vista ile gelen özellikten de [Kenny Kerr][8]&#8216;in Ağustos ayında MSDN Magazine&#8217;de yayınlamış olduğu [Windows Vista Control Enhancements][9] yazısını okuyunca haberim oldu. 

Kontrolü ve demosunu içeren [Visual Studio 2008 projesi][10] (12kb)

 [1]: http://www.alistapart.com/articles/makingcompactformsmoreaccessible/ "Making Compact Forms More Accessible by Mike Brittain"
 [2]: http://msdn2.microsoft.com/en-us/library/bb761639(VS.85).aspx
 [3]: http://www.pinvoke.net/default.aspx/user32.SendMessage
 [4]: http://msdn2.microsoft.com/en-us/library/aa719104.aspx "Using P/Invoke to Call Unmanaged APIs from Your Managed Classes"
 [5]: http://msdn2.microsoft.com/en-us/library/system.runtime.interopservices.handleref.aspx
 [6]: http://channel9.msdn.com/Niners/littleguru
 [7]: http://channel9.msdn.com/ShowPost.aspx?PostID=208895
 [8]: http://weblogs.asp.net/kennykerr/
 [9]: http://msdn.microsoft.com/msdnmag/issues/07/08/WindowsCPP/default.aspx#S2
 [10]: http://huseyint.com/projeler/CueBannerTextBox_Control.zip