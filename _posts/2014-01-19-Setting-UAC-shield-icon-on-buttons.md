---
title: Setting UAC shield icon on buttons
author: Hüseyin Tüfekçilerli
date: 2014-01-19
layout: post
permalink: /2014/01/Setting-UAC-shield-icon-on-buttons/
comments: true
categories:
  - .NET
tags:
  - csharp
  - uac
  - icon
  - devexpress
---
Starting from Windows Vista, you should have noticed that actions which require administrative privileges and needs to elevate the current process, displays a shield icon right next to them. For example the **Change date and time...** button on Date and Time dialog requires admin rights to modify system date, hence displays a UAC shield right next to it: 

<p align="center">
  <img src="/img/p/DateAndTimeUAC.png" alt="Date and Time" />
</p>

If your application has any buttons or menu items like this, to confirm the Windows UX Guidelines, it is a good idea to display a UAC shield icon on your controls. [This CodeProject article](http://www.codeproject.com/Articles/18509/Add-a-UAC-shield-to-a-button-when-elevation-is-req) has a good example that shows how to add shield icons to regular WinForms buttons. The gist is the following P/Invoke code:

```csharp
const int BCM_FIRST = 0x1600; //Normal button
const int BCM_SETSHIELD = BCM_FIRST + 0x000C; //Elevated button

static void AddShieldToButton(Button b)
{
  b.FlatStyle = FlatStyle.System;
  SendMessage(b.Handle, BCM_SETSHIELD, 0, 0xFFFFFFFF);
}
```

Simply pass your ```Button``` instance reference to ```AddShieldToButton``` and you will have a nice UAC shield icon on your button on Windows Vista and above.

If you are using a third party library for your user interface controls (like [DevExpress WinForms](https://www.devexpress.com/products/net/controls/winforms/)) or you need to add an icon to a menu item rather than a button, this method won't work unfortunately. There is [a support ticket](http://www.devexpress.com/Support/Center/Question/Details/S133322) on DevExpress Support Center about this issue and the suggested workaround is to use the ```Image``` property of ```SimpleButton``` class. So you can save a 16px * 16px version of this shield icon in your project resources and then use it. This has a minor issue though, if you want to strictly stick to the look and feel of the operating system, you may have problem while using a single hard-coded image. The reason is, this icon on Windows Vista was a shield icon with 4 colors (red, green, blue and yellow) but since Windows 7, it has only 2 colors (blue and yellow). Who knows what it will become on Windows 9 (do not get surprised if this icon becomes a flat metro-style icon). Not a big deal but if you really want to blend in with Windows desktop, these small things matter.

<p align="center">
  <img src="/img/p/uac-shield-icons.png" alt="UAC Shield Icons on Windows Vista" />
</p>

Windows stores these resources in its DLL files and it is a good idea to reuse them. This way, we will stick to what operating system has as the UAC shield icon and won't need to maintain that image resource in our project. Even WinForms has a managed API to access these kind of icons like error, warning, information, shield, etc. You can get a shield ```Icon``` instance by accessing [```SystemIcons.Shield```](http://msdn.microsoft.com/en-us/library/system.drawing.systemicons.shield.aspx) property since .NET Framework 2.0 SP1. There are 2 issues with this approach:

1. ```SystemIcons.Shield``` returns an [```Icon```](http://msdn.microsoft.com/en-us/library/system.drawing.icon.aspx) instance whereas ```SimpleButton.Image``` expects an [```Image```](http://msdn.microsoft.com/en-us/library/system.drawing.image.aspx) instance.
2. The icon is in 32px * 32px size but a regular button on Windows should have a small 16px * 16px icon. (On 96 DPI desktops)

To resolve these issues, I have come up with the following piece of code:

```csharp
static void AddShieldToButton(SimpleButton b)
{
  var icon = Icon.FromHandle(SystemIcons.Shield.Handle);

  var iconSize = SystemInformation.SmallIconSize;
  var bitmap = new Bitmap(iconSize.Width, iconSize.Height);

  using (var g = Graphics.FromImage(bitmap))
  {
      g.InterpolationMode = InterpolationMode.HighQualityBicubic;
      g.DrawIcon(icon, new Rectangle(0, 0, iconSize.Width, iconSize.Height));
  }

  b.Image = bitmap;
}
```

This is what you get as a result with DevExpress controls using this method:

<p align="center">
  <img src="/img/p/DevExpress-UAC1.png" alt="DevExpress UAC SimpleButton" />
</p>

This is ugly, huh? The issues are:

1. Even though I am on Windows 8.1, I am getting Windows Vista's 4 color UAC shield icon. If you dig down the ```SystemIcons.Shield``` code with Reflector, you will see that the getter of this property first tries to load the system UAC shield icon using a P/Invoke code to ```LoadIconWithScaleDown``` function of ```comctl32.dll```. This call fails, you can see that on your Visual Studio output pane reporting a first chance exception of type ```EntryPointNotFoundException``` thrown. The reason is, ```LoadIconWithScaleDown``` seems to be removed on a later version of ```comctl32.dll```. Fortunately, the getter has a backup method which gets the icon loaded from the ```System.Drawing.dll``` embedded resources, which unfortunately is the old UAC shield icon:

    ```csharp
    new Icon(typeof(SystemIcons), "ShieldIcon.ico");
    ```

2. The resized icon looks rubbish. Even though we are using ```InterpolationMode.HighQualityBicubic```, scaling down a 32px icon to 16px doesn't end well.

Digging down a bit more, I have finally managed to get all these issues sorted with the following piece of code:

```csharp
[DllImport("user32.dll")]
static extern IntPtr LoadImage(
    IntPtr hinst, 
    string lpszName, 
    uint uType, 
    int cxDesired, 
    int cyDesired, 
    uint fuLoad);

static void AddShieldIcon(SimpleButton button)
{
  var size = SystemInformation.SmallIconSize;
  var image = LoadImage(IntPtr.Zero, "#106", 1, size.Width, size.Height, 0);

  if (image == IntPtr.Zero)
  {
    return;
  }

  using (var icon = Icon.FromHandle(image))
  {
    var bitmap = new Bitmap(size.Width, size.Height);

    using (var g = Graphics.FromImage(bitmap))
    {
      g.DrawIcon(icon, new Rectangle(0, 0, size.Width, size.Height));
    }

    button.Image = bitmap;
  }
}
```

And this is the result:

<p align="center">
  <img src="/img/p/DevExpress-UAC2.png" alt="DevExpress UAC SimpleButton" />
</p>

Much better, if I say so myself. This one uses [```LoadImage```](http://msdn.microsoft.com/en-us/library/windows/desktop/ms648045.aspx) P/Invoke call to get the correct size of the icon, so we don't need to scale it down. Since this function returns an icon handle, we are just converting the icon to a bitmap. Using this method, you can even put UAC shield icons to your menu items:

<p align="center">
  <img src="/img/p/WinForms-UAC-MenuItem.png" alt="WinForms UAC menu item" />
</p>

Or you can get each and every size of the UAC shield icon, just because...you can:

<p align="center">
  <img src="/img/p/UAC-Icons.png" alt="UAC icons" />
</p>