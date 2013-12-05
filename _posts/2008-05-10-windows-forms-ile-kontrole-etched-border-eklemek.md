---
title: Windows Forms ile kontrole Etched border eklemek
author: Hüseyin Tüfekçilerli
date: 2008-05-10 -0800
layout: post
permalink: /2008/05/windows-forms-ile-kontrole-etched-border-eklemek/
comments: true
categories:
  - .NET
tags:
  - .NET
  - GUI
  - windows forms
---
Genellikle programların About dialog pencerelerinde görürüz bu şekilde çizgileri. Etched (kazınmış demekmiş) 3 boyutlu görüntüsü ile kullanıldığı yere göre pencereye modern bir hava katıyor. Firefox&#8217;un About penceresi mesela:

<p align="center">
  <img src="http://huseyint.com/wp-content/uploads/2008/05/firefox3-aboutdialog1.png" alt="Firefox 3 About dialog penceresi" title="Firefox 3 About dialog penceresi" width="316" height="467" />
</p>

Butonların hemen üstündeki çizgi pencerenin beyaz arkaplanlı üst ve gri arkaplanlı alt kısımlarını ayırmada kullanılmış ve 3 boyutlu bir görünüm kazandırmış. Visual Basic 6 ile böyle bir şeyi yapmak için Line kontrollerini kullanırdık. Birer adet gri ve beyaz renkli Line kontrolünü aralık bırakmadan alt alta gelecek şekilde formumuza yerlerştirdiğimizde bu görünümü elde edebilirdik. Visual Studio&#8217;nun .NET sonrası versiyonlarında böyle bir kontrol gelmemekte. Bana kalırsa gerek de yok çünkü Form üzerine Line tarzı kontroller koymak gereksiz yere tasarım ortamını karıştırıyor. Bunun yerine Windows Forms&#8217;un nimetlerinden yararlanarak bu sorunu çok daha şık bir şekilde çözebiliriz.

Yapmamız gereken [Panel][1] kontrolünün [OnPaint][2] metodunu override edip bize sağlanan [Graphics][3] nesnesini kullanarak [ControlPaint.DrawBorder3D][4] static metodu ile [istediğimiz kenara][5], [istediğimiz tarzda][6] bir border ekleyebiliriz. C# ile anlatacak olursam:

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #6666cc; font-weight: bold;">class</span> EtchedBorderedPanel <span style="color: #008000;">:</span> Panel
<span style="color: #008000;">&#123;</span>
    <span style="color: #0600FF; font-weight: bold;">protected</span> <span style="color: #0600FF; font-weight: bold;">override</span> <span style="color: #6666cc; font-weight: bold;">void</span> OnPaint<span style="color: #008000;">&#40;</span>PaintEventArgs e<span style="color: #008000;">&#41;</span>
    <span style="color: #008000;">&#123;</span>
        <span style="color: #0600FF; font-weight: bold;">base</span><span style="color: #008000;">.</span><span style="color: #0000FF;">OnPaint</span><span style="color: #008000;">&#40;</span>e<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
        ControlPaint<span style="color: #008000;">.</span><span style="color: #0000FF;">DrawBorder3D</span><span style="color: #008000;">&#40;</span>e<span style="color: #008000;">.</span><span style="color: #0000FF;">Graphics</span>, 
            <span style="color: #0600FF; font-weight: bold;">this</span><span style="color: #008000;">.</span><span style="color: #0000FF;">ClientRectangle</span>, 
            Border3DStyle<span style="color: #008000;">.</span><span style="color: #0000FF;">Etched</span>, 
            Border3DSide<span style="color: #008000;">.</span><span style="color: #0000FF;">Top</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
    <span style="color: #008000;">&#125;</span>
<span style="color: #008000;">&#125;</span></pre>
      </td>
    </tr>
  </table>
</div>

Bu kontrolü daha sonra penceremizin alt kısmına [Dock][7] edebiliriz. En son hafta sonu projem [FxLibrarian][8] için yaptığım About dialog penceresi:

<p align="center">
  <img src="http://huseyint.com/wp-content/uploads/2008/05/fxlibrarian-aboutdialog.png" alt="FxLibrarian About dialog penceresi" title="FxLibrarian About dialog penceresi" width="385" height="170" />
</p>

[CodeProject][9]&#8216;teki [şu makale][10] de böyle bir kontrolü nasıl yapacağınızı anlatıyor. Biraz eski ama tekrar kullanılabilir bir kontrol yapma konusunda güzel bilgiler içeriyor.

 [1]: http://msdn.microsoft.com/en-us/library/system.windows.forms.panel.aspx
 [2]: http://msdn.microsoft.com/en-us/library/system.windows.forms.control.onpaint.aspx
 [3]: http://msdn.microsoft.com/en-us/library/system.drawing.graphics.aspx
 [4]: http://msdn.microsoft.com/en-us/library/b39h02xk.aspx
 [5]: http://msdn.microsoft.com/en-us/library/ezxa8z32.aspx
 [6]: http://msdn.microsoft.com/en-us/library/system.windows.forms.border3dstyle.aspx
 [7]: http://msdn.microsoft.com/en-us/library/system.windows.forms.control.dock.aspx
 [8]: http://huseyint.com/FxLibrarian/
 [9]: http://www.codeproject.com/
 [10]: http://www.codeproject.com/KB/miscctrl/DividerPanel.aspx