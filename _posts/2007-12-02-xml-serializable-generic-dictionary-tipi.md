---
title: XML Serializable Generic Dictionary tipi
author: Hüseyin Tüfekçilerli
layout: post
permalink: /2007/12/xml-serializable-generic-dictionary-tipi/
comments: true
categories:
  - .NET
tags:
  - .NET
  - Dictionary
  - Serializable
  - XML
---
.NET ile kod yazarken en çok kullandığım collection tiplerinden biri olan [Dictionary][1] Generic yapısı ile de bizi bir çok casting işleminden kuratarıyor. Key olarak şu tipi, value olarak da şu tipi kullanacağız diyoruz ve key/value çiftlerini tiplerini de koruyarak tutabileceğimiz dört başı mamur bir listemiz olmuş oluyor. Bir de bu Dictionary tipimizi <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> olarak Serialize edebilsek tadından yenmeyecek ama nedendir bilinmez bunu yapamıyoruz. Yaparsak da şuna benzer bir Exception alıyoruz: 

> &#8220;The type System.Collections.Generic.Dictionary is not supported because it implements IDictionary.&#8221;

İnternette bu işi yapabilecek bir şeyler araştırdım ve [hatrı sayılır][2] [sitede][3] [bu sorunun][4] [farklı farklı][5] çözümlerine rastladım. Bunlardan en çok [Pete Welter&#8217;in çözümü][6] hoşuma gitti. Bu çözümde Pete Welter, Dictionary generic tipini ve [IXmlSerializable][7] tipini implement eden yeni bir tip yaratmış. Dictionary&#8217;den dolayı yeni tipimiz Dictionary ile beraber gelen tüm özelliklere sahip, oh ne güzel. IXmlSerializable tipinin gerektirdiği metodları da implement ederek <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> Serialization işlemi sırasında yeni tipimizin nasıl serialize edileceğini de bildirmiş sevgili Pete Welter. Sonuç olarak temiz ve çok rahat kullanılabilir yeni bir tip ortaya çıkmış. Kod şu şekilde: 

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #008000;">&#91;</span>XmlRoot<span style="color: #008000;">&#40;</span><span style="color: #666666;">"dictionary"</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">&#93;</span>
<span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #6666cc; font-weight: bold;">class</span> SerializableDictionary<span style="color: #008000;">&lt;</span>TKey, TValue<span style="color: #008000;">&gt;</span>
    <span style="color: #008000;">:</span> Dictionary<span style="color: #008000;">&lt;</span>TKey, TValue<span style="color: #008000;">&gt;</span>, IXmlSerializable
<span style="color: #008000;">&#123;</span>
    <span style="color: #008080;">#region IXmlSerializable Members</span>
    <span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #000000;">System.<span style="color: #0000FF;">Xml</span></span><span style="color: #008000;">.</span><span style="color: #0000FF;">Schema</span><span style="color: #008000;">.</span><span style="color: #0000FF;">XmlSchema</span> GetSchema<span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span>
    <span style="color: #008000;">&#123;</span>
        <span style="color: #0600FF; font-weight: bold;">return</span> <span style="color: #0600FF; font-weight: bold;">null</span><span style="color: #008000;">;</span>
    <span style="color: #008000;">&#125;</span>
&nbsp;
    <span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #6666cc; font-weight: bold;">void</span> ReadXml<span style="color: #008000;">&#40;</span><span style="color: #000000;">System.<span style="color: #0000FF;">Xml</span></span><span style="color: #008000;">.</span><span style="color: #0000FF;">XmlReader</span> reader<span style="color: #008000;">&#41;</span>
    <span style="color: #008000;">&#123;</span>
        XmlSerializer keySerializer <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>TKey<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
        XmlSerializer valueSerializer <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>TValue<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
        <span style="color: #6666cc; font-weight: bold;">bool</span> wasEmpty <span style="color: #008000;">=</span> reader<span style="color: #008000;">.</span><span style="color: #0000FF;">IsEmptyElement</span><span style="color: #008000;">;</span>
        reader<span style="color: #008000;">.</span><span style="color: #0000FF;">Read</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
        <span style="color: #0600FF; font-weight: bold;">if</span> <span style="color: #008000;">&#40;</span>wasEmpty<span style="color: #008000;">&#41;</span>
            <span style="color: #0600FF; font-weight: bold;">return</span><span style="color: #008000;">;</span>
&nbsp;
        <span style="color: #0600FF; font-weight: bold;">while</span> <span style="color: #008000;">&#40;</span>reader<span style="color: #008000;">.</span><span style="color: #0000FF;">NodeType</span> <span style="color: #008000;">!=</span> <span style="color: #000000;">System.<span style="color: #0000FF;">Xml</span></span><span style="color: #008000;">.</span><span style="color: #0000FF;">XmlNodeType</span><span style="color: #008000;">.</span><span style="color: #0000FF;">EndElement</span><span style="color: #008000;">&#41;</span>
        <span style="color: #008000;">&#123;</span>
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadStartElement</span><span style="color: #008000;">&#40;</span><span style="color: #666666;">"item"</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadStartElement</span><span style="color: #008000;">&#40;</span><span style="color: #666666;">"key"</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            TKey key <span style="color: #008000;">=</span> <span style="color: #008000;">&#40;</span>TKey<span style="color: #008000;">&#41;</span>keySerializer<span style="color: #008000;">.</span><span style="color: #0000FF;">Deserialize</span><span style="color: #008000;">&#40;</span>reader<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadStartElement</span><span style="color: #008000;">&#40;</span><span style="color: #666666;">"value"</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            TValue <span style="color: #0600FF; font-weight: bold;">value</span> <span style="color: #008000;">=</span> <span style="color: #008000;">&#40;</span>TValue<span style="color: #008000;">&#41;</span>valueSerializer<span style="color: #008000;">.</span><span style="color: #0000FF;">Deserialize</span><span style="color: #008000;">&#40;</span>reader<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            <span style="color: #0600FF; font-weight: bold;">this</span><span style="color: #008000;">.</span><span style="color: #0600FF; font-weight: bold;">Add</span><span style="color: #008000;">&#40;</span>key, <span style="color: #0600FF; font-weight: bold;">value</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">MoveToContent</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
        <span style="color: #008000;">&#125;</span>
        reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
    <span style="color: #008000;">&#125;</span>
&nbsp;
    <span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #6666cc; font-weight: bold;">void</span> WriteXml<span style="color: #008000;">&#40;</span><span style="color: #000000;">System.<span style="color: #0000FF;">Xml</span></span><span style="color: #008000;">.</span><span style="color: #0000FF;">XmlWriter</span> writer<span style="color: #008000;">&#41;</span>
    <span style="color: #008000;">&#123;</span>
        XmlSerializer keySerializer <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>TKey<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
        XmlSerializer valueSerializer <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>TValue<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
        <span style="color: #0600FF; font-weight: bold;">foreach</span> <span style="color: #008000;">&#40;</span>TKey key <span style="color: #0600FF; font-weight: bold;">in</span> <span style="color: #0600FF; font-weight: bold;">this</span><span style="color: #008000;">.</span><span style="color: #0000FF;">Keys</span><span style="color: #008000;">&#41;</span>
        <span style="color: #008000;">&#123;</span>
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteStartElement</span><span style="color: #008000;">&#40;</span><span style="color: #666666;">"item"</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteStartElement</span><span style="color: #008000;">&#40;</span><span style="color: #666666;">"key"</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            keySerializer<span style="color: #008000;">.</span><span style="color: #0000FF;">Serialize</span><span style="color: #008000;">&#40;</span>writer, key<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteStartElement</span><span style="color: #008000;">&#40;</span><span style="color: #666666;">"value"</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            TValue <span style="color: #0600FF; font-weight: bold;">value</span> <span style="color: #008000;">=</span> <span style="color: #0600FF; font-weight: bold;">this</span><span style="color: #008000;">&#91;</span>key<span style="color: #008000;">&#93;</span><span style="color: #008000;">;</span>
            valueSerializer<span style="color: #008000;">.</span><span style="color: #0000FF;">Serialize</span><span style="color: #008000;">&#40;</span>writer, <span style="color: #0600FF; font-weight: bold;">value</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
        <span style="color: #008000;">&#125;</span>
    <span style="color: #008000;">&#125;</span>
    <span style="color: #008080;">#endregion</span>
<span style="color: #008000;">&#125;</span></pre>
      </td>
    </tr>
  </table>
</div>

Dictionary nesnemiz için serialize edilebilen key ve value tipleri seçtiğimiz sürece SerializableDictionary tipimiz de serialize edilebilir. Key/value tipleri olarak string kullandığımız bir SerializableDictionary nesnesini serialize ettiğimizde şöyle bir çıktıya sahip oluyor: 

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="xml" style="font-family:monospace;"><span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;dictionary<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
  <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;item<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;key<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
      <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>foo<span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/key<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;value<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
      <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>bar<span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/value<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
  <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/item<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
<span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/dictionary<span style="color: #000000; font-weight: bold;">&gt;</span></span></span></pre>
      </td>
    </tr>
  </table>
</div>

Buraya kadar her şey güzel, Dictionary yerine bu yeni tipi kullanarak varolan Dictionary yapılarımızı <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> olarak da serialize edebiliriz artık. Ama bir şey içime sinmiyor hala, <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> çıktısındaki etiketlerin isimlerini SerializableDictionary üzerinde hard-coded olarak yazıyoruz. Kökteki etiket ismini XmlRootAttribute&#8217;e farklı bir parametre göndererek veya SerializableDictionary tipini extend eden yeni bir tip tanımlayarak değiştirebiliriz. Ama hala alt seviyelerdeki **item**, **key** ve **value** şeklindeki etiket isimlerini hard-code etmek dışında bir seçeneğimiz görünmüyor. Eğer bu sınıfı tek tipteki bir Dictionary verimiz için kullanacaksak pek bir sorun yok ama farklı farklı <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> çıktılarını beklediğimiz generic bir çözüm için bu tipi kullanmayı planlıyorsak bu etiket isimlerine müdahale etmenin daha generic bir yolunu bulmamız gerek (copy/paste yapmayı bir çözüm olarak aklımızdan bile geçirmiyoruz tabii ki). Bu amaçla SerializableDictionary tipini biraz değiştirerek şu kodu elde ettim: 

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #0600FF; font-weight: bold;">abstract</span> <span style="color: #6666cc; font-weight: bold;">class</span> SerializableDictionary<span style="color: #008000;">&lt;</span>TKey, TValue<span style="color: #008000;">&gt;</span> 
    <span style="color: #008000;">:</span> Dictionary<span style="color: #008000;">&lt;</span>TKey, TValue<span style="color: #008000;">&gt;</span>, IXmlSerializable
<span style="color: #008000;">&#123;</span>
    <span style="color: #0600FF; font-weight: bold;">protected</span> <span style="color: #0600FF; font-weight: bold;">abstract</span> <span style="color: #6666cc; font-weight: bold;">string</span> itemName <span style="color: #008000;">&#123;</span> <span style="color: #0600FF; font-weight: bold;">get</span><span style="color: #008000;">;</span> <span style="color: #008000;">&#125;</span>
    <span style="color: #0600FF; font-weight: bold;">protected</span> <span style="color: #0600FF; font-weight: bold;">abstract</span> <span style="color: #6666cc; font-weight: bold;">string</span> keyName <span style="color: #008000;">&#123;</span> <span style="color: #0600FF; font-weight: bold;">get</span><span style="color: #008000;">;</span> <span style="color: #008000;">&#125;</span>
    <span style="color: #0600FF; font-weight: bold;">protected</span> <span style="color: #0600FF; font-weight: bold;">abstract</span> <span style="color: #6666cc; font-weight: bold;">string</span> valueName <span style="color: #008000;">&#123;</span> <span style="color: #0600FF; font-weight: bold;">get</span><span style="color: #008000;">;</span> <span style="color: #008000;">&#125;</span>
&nbsp;
    <span style="color: #008080;">#region IXmlSerializable Members</span>
    <span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #000000;">System.<span style="color: #0000FF;">Xml</span></span><span style="color: #008000;">.</span><span style="color: #0000FF;">Schema</span><span style="color: #008000;">.</span><span style="color: #0000FF;">XmlSchema</span> GetSchema<span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span>
    <span style="color: #008000;">&#123;</span>
        <span style="color: #0600FF; font-weight: bold;">return</span> <span style="color: #0600FF; font-weight: bold;">null</span><span style="color: #008000;">;</span>
    <span style="color: #008000;">&#125;</span>
&nbsp;
    <span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #6666cc; font-weight: bold;">void</span> ReadXml<span style="color: #008000;">&#40;</span>XmlReader reader<span style="color: #008000;">&#41;</span>
    <span style="color: #008000;">&#123;</span>
        XmlSerializer keySerializer <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>TKey<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
        XmlSerializer valueSerializer <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>TValue<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
        <span style="color: #6666cc; font-weight: bold;">bool</span> wasEmpty <span style="color: #008000;">=</span> reader<span style="color: #008000;">.</span><span style="color: #0000FF;">IsEmptyElement</span><span style="color: #008000;">;</span>
        reader<span style="color: #008000;">.</span><span style="color: #0000FF;">Read</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
        <span style="color: #0600FF; font-weight: bold;">if</span> <span style="color: #008000;">&#40;</span>wasEmpty<span style="color: #008000;">&#41;</span>
            <span style="color: #0600FF; font-weight: bold;">return</span><span style="color: #008000;">;</span>
&nbsp;
        <span style="color: #0600FF; font-weight: bold;">while</span> <span style="color: #008000;">&#40;</span>reader<span style="color: #008000;">.</span><span style="color: #0000FF;">NodeType</span> <span style="color: #008000;">!=</span> <span style="color: #000000;">System.<span style="color: #0000FF;">Xml</span></span><span style="color: #008000;">.</span><span style="color: #0000FF;">XmlNodeType</span><span style="color: #008000;">.</span><span style="color: #0000FF;">EndElement</span><span style="color: #008000;">&#41;</span>
        <span style="color: #008000;">&#123;</span>
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadStartElement</span><span style="color: #008000;">&#40;</span>itemName<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadStartElement</span><span style="color: #008000;">&#40;</span>keyName<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            TKey key <span style="color: #008000;">=</span> <span style="color: #008000;">&#40;</span>TKey<span style="color: #008000;">&#41;</span>keySerializer<span style="color: #008000;">.</span><span style="color: #0000FF;">Deserialize</span><span style="color: #008000;">&#40;</span>reader<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadStartElement</span><span style="color: #008000;">&#40;</span>valueName<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            TValue <span style="color: #0600FF; font-weight: bold;">value</span> <span style="color: #008000;">=</span> <span style="color: #008000;">&#40;</span>TValue<span style="color: #008000;">&#41;</span>valueSerializer<span style="color: #008000;">.</span><span style="color: #0000FF;">Deserialize</span><span style="color: #008000;">&#40;</span>reader<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            <span style="color: #0600FF; font-weight: bold;">this</span><span style="color: #008000;">.</span><span style="color: #0600FF; font-weight: bold;">Add</span><span style="color: #008000;">&#40;</span>key, <span style="color: #0600FF; font-weight: bold;">value</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            reader<span style="color: #008000;">.</span><span style="color: #0000FF;">MoveToContent</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
        <span style="color: #008000;">&#125;</span>
        reader<span style="color: #008000;">.</span><span style="color: #0000FF;">ReadEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
    <span style="color: #008000;">&#125;</span>
&nbsp;
    <span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #6666cc; font-weight: bold;">void</span> WriteXml<span style="color: #008000;">&#40;</span><span style="color: #000000;">System.<span style="color: #0000FF;">Xml</span></span><span style="color: #008000;">.</span><span style="color: #0000FF;">XmlWriter</span> writer<span style="color: #008000;">&#41;</span>
    <span style="color: #008000;">&#123;</span>
        XmlSerializer keySerializer <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>TKey<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
        XmlSerializer valueSerializer <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>TValue<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
        <span style="color: #0600FF; font-weight: bold;">foreach</span> <span style="color: #008000;">&#40;</span>TKey key <span style="color: #0600FF; font-weight: bold;">in</span> <span style="color: #0600FF; font-weight: bold;">this</span><span style="color: #008000;">.</span><span style="color: #0000FF;">Keys</span><span style="color: #008000;">&#41;</span>
        <span style="color: #008000;">&#123;</span>
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteStartElement</span><span style="color: #008000;">&#40;</span>itemName<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
&nbsp;
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteStartElement</span><span style="color: #008000;">&#40;</span>keyName<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            keySerializer<span style="color: #008000;">.</span><span style="color: #0000FF;">Serialize</span><span style="color: #008000;">&#40;</span>writer, key<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteStartElement</span><span style="color: #008000;">&#40;</span>valueName<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            TValue <span style="color: #0600FF; font-weight: bold;">value</span> <span style="color: #008000;">=</span> <span style="color: #0600FF; font-weight: bold;">this</span><span style="color: #008000;">&#91;</span>key<span style="color: #008000;">&#93;</span><span style="color: #008000;">;</span>
            valueSerializer<span style="color: #008000;">.</span><span style="color: #0000FF;">Serialize</span><span style="color: #008000;">&#40;</span>writer, <span style="color: #0600FF; font-weight: bold;">value</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
            writer<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteEndElement</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
        <span style="color: #008000;">&#125;</span>
    <span style="color: #008000;">&#125;</span>
    <span style="color: #008080;">#endregion</span>
<span style="color: #008000;">&#125;</span></pre>
      </td>
    </tr>
  </table>
</div>

Override edilmek üzere 3 tane abstract property ekleyerek hard-coded string değerlerinden kurtulmuş olduk. Tipimize abstract üyeler eklediğimiz için artık bu tipi direk olarak kullanılamaz hale getirdik ve bir abstract tipimiz oldu. Tipimizi kullanmak istediğimizde yamamız gereken kod artık şu şekilde olacak: 

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #0600FF; font-weight: bold;">public</span> <span style="color: #6666cc; font-weight: bold;">class</span> Fields <span style="color: #008000;">:</span> SerializableDictionary<span style="color: #008000;">&lt;</span><span style="color: #6666cc; font-weight: bold;">string</span>, <span style="color: #6666cc; font-weight: bold;">string</span><span style="color: #008000;">&gt;</span>
<span style="color: #008000;">&#123;</span>
    <span style="color: #0600FF; font-weight: bold;">protected</span> <span style="color: #0600FF; font-weight: bold;">override</span> <span style="color: #6666cc; font-weight: bold;">string</span> itemName
    <span style="color: #008000;">&#123;</span>
        <span style="color: #0600FF; font-weight: bold;">get</span> <span style="color: #008000;">&#123;</span> <span style="color: #0600FF; font-weight: bold;">return</span> <span style="color: #666666;">"Field"</span><span style="color: #008000;">;</span> <span style="color: #008000;">&#125;</span>
    <span style="color: #008000;">&#125;</span>
&nbsp;
    <span style="color: #0600FF; font-weight: bold;">protected</span> <span style="color: #0600FF; font-weight: bold;">override</span> <span style="color: #6666cc; font-weight: bold;">string</span> keyName
    <span style="color: #008000;">&#123;</span>
        <span style="color: #0600FF; font-weight: bold;">get</span> <span style="color: #008000;">&#123;</span> <span style="color: #0600FF; font-weight: bold;">return</span> <span style="color: #666666;">"Name"</span><span style="color: #008000;">;</span> <span style="color: #008000;">&#125;</span>
    <span style="color: #008000;">&#125;</span>
&nbsp;
    <span style="color: #0600FF; font-weight: bold;">protected</span> <span style="color: #0600FF; font-weight: bold;">override</span> <span style="color: #6666cc; font-weight: bold;">string</span> valueName
    <span style="color: #008000;">&#123;</span>
        <span style="color: #0600FF; font-weight: bold;">get</span> <span style="color: #008000;">&#123;</span> <span style="color: #0600FF; font-weight: bold;">return</span> <span style="color: #666666;">"Value"</span><span style="color: #008000;">;</span> <span style="color: #008000;">&#125;</span>
    <span style="color: #008000;">&#125;</span>
<span style="color: #008000;">&#125;</span></pre>
      </td>
    </tr>
  </table>
</div>

Burada az önce değiştirdiğimiz SerializableDictionary tipini extend ederek kullanacağımız yeni tipi yaratıyoruz. Bu aşmada Key/Value tiplerimizi ve <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> çıktısında oluşacak etiketlerin isimlerini belirtiyoruz. Eğer kök <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> etiketi için kullanılacak ismin tipimizin isminden farklı olmasını istiyorsanız yeni tipin üzerinde XmlRootAttribute kullanarak bunu da belirtebilirsiniz. Bu tipimizi de artık şu şekilde kullanabiliriz: 

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="csharp" style="font-family:monospace;"><span style="color: #008080; font-style: italic;">// 2 kayıt içeren bir Dictionary oluşturalım</span>
<span style="color: #008080; font-style: italic;">// .NET 3.5/VS2008 ile gelen Collection Initializers özelliği</span>
<span style="color: #008080; font-style: italic;">// bu gibi durumlarda daha temiz/kısa kod yazmamıza yarıyor</span>
Fields fields <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> Fields <span style="color: #008000;">&#123;</span>
    <span style="color: #008000;">&#123;</span> <span style="color: #666666;">"foo"</span>, <span style="color: #666666;">"bar"</span> <span style="color: #008000;">&#125;</span>,
    <span style="color: #008000;">&#123;</span> <span style="color: #666666;">"hede"</span>, <span style="color: #666666;">"hödö"</span><span style="color: #008000;">&#125;</span>
<span style="color: #008000;">&#125;</span><span style="color: #008000;">;</span>
&nbsp;
<span style="color: #008080; font-style: italic;">// XML Serialize işlemi için gerekli altyapıyı oluşturalım</span>
StringBuilder sb <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> StringBuilder<span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
<span style="color: #008080; font-style: italic;">// Burada da bir başka yenilik olan Object Initializer özelliğini</span>
<span style="color: #008080; font-style: italic;">// kullanarak nesneyi oluşturduğumuz satırda nesne üzerindeki</span>
<span style="color: #008080; font-style: italic;">// bazı özelliklere de ilk değerlerini verebiliyoruz</span>
XmlTextWriter xtw <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlTextWriter<span style="color: #008000;">&#40;</span><span style="color: #008000;">new</span> StringWriter<span style="color: #008000;">&#40;</span>sb<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span> 
    <span style="color: #008000;">&#123;</span> Formatting <span style="color: #008000;">=</span> Formatting<span style="color: #008000;">.</span><span style="color: #0000FF;">Indented</span> <span style="color: #008000;">&#125;</span><span style="color: #008000;">;</span>
xtw<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteRaw</span><span style="color: #008000;">&#40;</span><span style="color: #666666;">""</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
<span style="color: #008080; font-style: italic;">// Burada XML Serialize işlemi gerçekleşiyor</span>
XmlSerializer xs <span style="color: #008000;">=</span> <span style="color: #008000;">new</span> XmlSerializer<span style="color: #008000;">&#40;</span><span style="color: #008000;">typeof</span><span style="color: #008000;">&#40;</span>Fields<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
xs<span style="color: #008000;">.</span><span style="color: #0000FF;">Serialize</span><span style="color: #008000;">&#40;</span>xtw, fields<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
<span style="color: #6666cc; font-weight: bold;">string</span> xmlFields <span style="color: #008000;">=</span> sb<span style="color: #008000;">.</span><span style="color: #0000FF;">ToString</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
Console<span style="color: #008000;">.</span><span style="color: #0000FF;">WriteLine</span><span style="color: #008000;">&#40;</span>xmlFields<span style="color: #008000;">&#41;</span><span style="color: #008000;">;</span>
&nbsp;
<span style="color: #008080; font-style: italic;">// Burada da daha önce serialize ettiğimiz nesneyi deserialize ederek</span>
<span style="color: #008080; font-style: italic;">// tekrar hafızda bir nesne haline çeviriyoruz</span>
Fields deserializedFields <span style="color: #008000;">=</span> xs<span style="color: #008000;">.</span><span style="color: #0000FF;">Deserialize</span><span style="color: #008000;">&#40;</span><span style="color: #008000;">new</span> StringReader<span style="color: #008000;">&#40;</span>xmlFields<span style="color: #008000;">&#41;</span><span style="color: #008000;">&#41;</span> <span style="color: #0600FF; font-weight: bold;">as</span> Fields<span style="color: #008000;">;</span></pre>
      </td>
    </tr>
  </table>
</div>

Bu kodun çıktısı da şu şekilde: 

<div class="wp_syntax">
  <table>
    <tr>
      <td class="code">
        <pre class="xml" style="font-family:monospace;"><span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;Fields<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
  <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;Field<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;Name<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
      <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>foo<span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/Name<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;Value<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
      <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>bar<span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/Value<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
  <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/Field<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
  <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;Field<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;Name<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
      <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>hede<span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/Name<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;Value<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
      <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>hödö<span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/string<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
    <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/Value<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
  <span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/Field<span style="color: #000000; font-weight: bold;">&gt;</span></span></span>
<span style="color: #009900;"><span style="color: #000000; font-weight: bold;">&lt;/Fields<span style="color: #000000; font-weight: bold;">&gt;</span></span></span></pre>
      </td>
    </tr>
  </table>
</div>

SerializableDictionary tipini bir kere tanımlayarak projemiz içerisinde bunu baz alan farklı farklı Dictionary tiplerimizi oluşturabilir, bunların farklı farklı <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> çıktıları olmasını sağlayabiliriz.

 [1]: http://msdn2.microsoft.com/en-us/library/xfhwa508(VS.90).aspx
 [2]: http://www.sitepoint.com/blogs/2006/07/09/generic-dictionaries-vs-the-xmlserializer/
 [3]: http://aspzone.com/blogs/john/articles/167.aspx
 [4]: http://msdn.microsoft.com/msdnmag/issues/03/06/XMLFiles/default.aspx#QA7
 [5]: http://blogs.msdn.com/psheill/archive/2005/04/09/406823.aspx
 [6]: http://weblogs.asp.net/pwelter34/archive/2006/05/03/444961.aspx
 [7]: http://msdn2.microsoft.com/en-us/library/system.xml.serialization.ixmlserializable.aspx