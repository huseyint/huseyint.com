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

```csharp
[XmlRoot("dictionary")]
public class SerializableDictionary<TKey, TValue>
    : Dictionary<TKey, TValue>, IXmlSerializable
{
    #region IXmlSerializable Members
    public System.Xml.Schema.XmlSchema GetSchema()
    {
        return null;
    }
 
    public void ReadXml(System.Xml.XmlReader reader)
    {
        XmlSerializer keySerializer = new XmlSerializer(typeof(TKey));
        XmlSerializer valueSerializer = new XmlSerializer(typeof(TValue));
 
        bool wasEmpty = reader.IsEmptyElement;
        reader.Read();
 
        if (wasEmpty)
            return;
 
        while (reader.NodeType != System.Xml.XmlNodeType.EndElement)
        {
            reader.ReadStartElement("item");
 
            reader.ReadStartElement("key");
            TKey key = (TKey)keySerializer.Deserialize(reader);
            reader.ReadEndElement();
 
            reader.ReadStartElement("value");
            TValue value = (TValue)valueSerializer.Deserialize(reader);
            reader.ReadEndElement();
 
            this.Add(key, value);
 
            reader.ReadEndElement();
            reader.MoveToContent();
        }
        reader.ReadEndElement();
    }
 
    public void WriteXml(System.Xml.XmlWriter writer)
    {
        XmlSerializer keySerializer = new XmlSerializer(typeof(TKey));
        XmlSerializer valueSerializer = new XmlSerializer(typeof(TValue));
 
        foreach (TKey key in this.Keys)
        {
            writer.WriteStartElement("item");
 
            writer.WriteStartElement("key");
            keySerializer.Serialize(writer, key);
            writer.WriteEndElement();
 
            writer.WriteStartElement("value");
            TValue value = this[key];
            valueSerializer.Serialize(writer, value);
            writer.WriteEndElement();
 
            writer.WriteEndElement();
        }
    }
    #endregion
}
```

Dictionary nesnemiz için serialize edilebilen key ve value tipleri seçtiğimiz sürece SerializableDictionary tipimiz de serialize edilebilir. Key/value tipleri olarak string kullandığımız bir SerializableDictionary nesnesini serialize ettiğimizde şöyle bir çıktıya sahip oluyor: 

```xml
<dictionary>
  <item>
    <key>
      <string>foo</string>
    </key>
    <value>
      <string>bar</string>
    </value>
  </item>
</dictionary>
```

Buraya kadar her şey güzel, Dictionary yerine bu yeni tipi kullanarak varolan Dictionary yapılarımızı <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> olarak da serialize edebiliriz artık. Ama bir şey içime sinmiyor hala, <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> çıktısındaki etiketlerin isimlerini SerializableDictionary üzerinde hard-coded olarak yazıyoruz. Kökteki etiket ismini XmlRootAttribute&#8217;e farklı bir parametre göndererek veya SerializableDictionary tipini extend eden yeni bir tip tanımlayarak değiştirebiliriz. Ama hala alt seviyelerdeki **item**, **key** ve **value** şeklindeki etiket isimlerini hard-code etmek dışında bir seçeneğimiz görünmüyor. Eğer bu sınıfı tek tipteki bir Dictionary verimiz için kullanacaksak pek bir sorun yok ama farklı farklı <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> çıktılarını beklediğimiz generic bir çözüm için bu tipi kullanmayı planlıyorsak bu etiket isimlerine müdahale etmenin daha generic bir yolunu bulmamız gerek (copy/paste yapmayı bir çözüm olarak aklımızdan bile geçirmiyoruz tabii ki). Bu amaçla SerializableDictionary tipini biraz değiştirerek şu kodu elde ettim: 

```csharp
public abstract class SerializableDictionary<TKey, TValue> 
    : Dictionary<TKey, TValue>, IXmlSerializable
{
    protected abstract string itemName { get; }
    protected abstract string keyName { get; }
    protected abstract string valueName { get; }
 
    #region IXmlSerializable Members
    public System.Xml.Schema.XmlSchema GetSchema()
    {
        return null;
    }
 
    public void ReadXml(XmlReader reader)
    {
        XmlSerializer keySerializer = new XmlSerializer(typeof(TKey));
        XmlSerializer valueSerializer = new XmlSerializer(typeof(TValue));
 
        bool wasEmpty = reader.IsEmptyElement;
        reader.Read();
 
        if (wasEmpty)
            return;
 
        while (reader.NodeType != System.Xml.XmlNodeType.EndElement)
        {
            reader.ReadStartElement(itemName);
 
            reader.ReadStartElement(keyName);
            TKey key = (TKey)keySerializer.Deserialize(reader);
            reader.ReadEndElement();
 
            reader.ReadStartElement(valueName);
            TValue value = (TValue)valueSerializer.Deserialize(reader);
            reader.ReadEndElement();
 
            this.Add(key, value);
 
            reader.ReadEndElement();
            reader.MoveToContent();
        }
        reader.ReadEndElement();
    }
 
    public void WriteXml(System.Xml.XmlWriter writer)
    {
        XmlSerializer keySerializer = new XmlSerializer(typeof(TKey));
        XmlSerializer valueSerializer = new XmlSerializer(typeof(TValue));
 
        foreach (TKey key in this.Keys)
        {
            writer.WriteStartElement(itemName);
 
 
            writer.WriteStartElement(keyName);
            keySerializer.Serialize(writer, key);
            writer.WriteEndElement();
 
            writer.WriteStartElement(valueName);
            TValue value = this[key];
            valueSerializer.Serialize(writer, value);
            writer.WriteEndElement();
 
            writer.WriteEndElement();
        }
    }
    #endregion
}
```

Override edilmek üzere 3 tane abstract property ekleyerek hard-coded string değerlerinden kurtulmuş olduk. Tipimize abstract üyeler eklediğimiz için artık bu tipi direk olarak kullanılamaz hale getirdik ve bir abstract tipimiz oldu. Tipimizi kullanmak istediğimizde yamamız gereken kod artık şu şekilde olacak: 

```csharp
public class Fields : SerializableDictionary<string, string>
{
    protected override string itemName
    {
        get { return "Field"; }
    }
 
    protected override string keyName
    {
        get { return "Name"; }
    }
 
    protected override string valueName
    {
        get { return "Value"; }
    }
}
```

Burada az önce değiştirdiğimiz SerializableDictionary tipini extend ederek kullanacağımız yeni tipi yaratıyoruz. Bu aşmada Key/Value tiplerimizi ve <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> çıktısında oluşacak etiketlerin isimlerini belirtiyoruz. Eğer kök <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> etiketi için kullanılacak ismin tipimizin isminden farklı olmasını istiyorsanız yeni tipin üzerinde XmlRootAttribute kullanarak bunu da belirtebilirsiniz. Bu tipimizi de artık şu şekilde kullanabiliriz: 

```csharp
// 2 kayıt içeren bir Dictionary oluşturalım
// .NET 3.5/VS2008 ile gelen Collection Initializers özelliği
// bu gibi durumlarda daha temiz/kısa kod yazmamıza yarıyor
Fields fields = new Fields {
    { "foo", "bar" },
    { "hede", "hödö"}
};
 
// XML Serialize işlemi için gerekli altyapıyı oluşturalım
StringBuilder sb = new StringBuilder();
 
// Burada da bir başka yenilik olan Object Initializer özelliğini
// kullanarak nesneyi oluşturduğumuz satırda nesne üzerindeki
// bazı özelliklere de ilk değerlerini verebiliyoruz
XmlTextWriter xtw = new XmlTextWriter(new StringWriter(sb)) 
    { Formatting = Formatting.Indented };
xtw.WriteRaw("");
 
// Burada XML Serialize işlemi gerçekleşiyor
XmlSerializer xs = new XmlSerializer(typeof(Fields));
xs.Serialize(xtw, fields);
 
string xmlFields = sb.ToString();
 
Console.WriteLine(xmlFields);
 
// Burada da daha önce serialize ettiğimiz nesneyi deserialize ederek
// tekrar hafızda bir nesne haline çeviriyoruz
Fields deserializedFields = xs.Deserialize(new StringReader(xmlFields)) as Fields;
```

Bu kodun çıktısı da şu şekilde: 

```xml
<Fields>
  <Field>
    <Name>
      <string>foo</string>
    </Name>
    <Value>
      <string>bar</string>
    </Value>
  </Field>
  <Field>
    <Name>
      <string>hede</string>
    </Name>
    <Value>
      <string>hödö</string>
    </Value>
  </Field>
</Fields>
```

SerializableDictionary tipini bir kere tanımlayarak projemiz içerisinde bunu baz alan farklı farklı Dictionary tiplerimizi oluşturabilir, bunların farklı farklı <acronym class="uttInitialism" title="eXtensible Markup Language">XML</acronym> çıktıları olmasını sağlayabiliriz.

 [1]: http://msdn2.microsoft.com/en-us/library/xfhwa508(VS.90).aspx
 [2]: http://www.sitepoint.com/blogs/2006/07/09/generic-dictionaries-vs-the-xmlserializer/
 [3]: http://aspzone.com/blogs/john/articles/167.aspx
 [4]: http://msdn.microsoft.com/msdnmag/issues/03/06/XMLFiles/default.aspx#QA7
 [5]: http://blogs.msdn.com/psheill/archive/2005/04/09/406823.aspx
 [6]: http://weblogs.asp.net/pwelter34/archive/2006/05/03/444961.aspx
 [7]: http://msdn2.microsoft.com/en-us/library/system.xml.serialization.ixmlserializable.aspx