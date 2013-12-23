---
title: Have your JSON TreeView and unit test it too
author: Hüseyin Tüfekçilerli
date: 2013-12-23
layout: post
permalink: /2013/12/Have-your-JSON-TreeView-and-unit-test-it-too/
comments: true
categories:
  - .NET
tags:
  - csharp
  - json
  - treeview
  - winforms
---
The other day I was tasked to load XML and JSON documents to a WinForms ```TreeView``` control. Nothing fancy, obviously a recursive method will do the job but I thought this should be one of those already solved problems and I can easily find a snippet on the interwebs. For XML a [simple Google search](https://www.google.com/search?q=load+xml+treeview+winforms) lead me to [the expected source code snippet](http://support.microsoft.com/kb/317597) and after a few refactoring here and there, I was done.

But for the JSON, the first page of Google search results failed me and I couldn't find that single method snippet, preferably using the great [Newtonsoft.JSON](http://json.codeplex.com/) library. So I have given it a spin and get my hands dirty, after all it is good to refresh my rusty recursive coding skills :) I hope the next guy finds the following code useful and saves him a better half/one hour time.

```csharp
void LoadJsonToTreeView(TreeView treeView, string json)
{
  if (string.IsNullOrWhiteSpace(json))
  {
    return;
  }

  var @object = JObject.Parse(json);
  AddObjectNodes(@object, "JSON", treeView.Nodes);
}

void AddObjectNodes(JObject @object, string name, TreeNodeCollection parent)
{
  var node = new TreeNode(name);
  parent.Add(node);

  foreach (var property in @object.Properties())
  {
    AddTokenNodes(property.Value, property.Name, node.Nodes);
  }
}

void AddArrayNodes(JArray array, string name, TreeNodeCollection parent)
{
  var node = new TreeNode(name);
  parent.Add(node);

  for (var i = 0; i < array.Count; i++)
  {
    AddTokenNodes(array[i], string.Format("[{0}]", i), node.Nodes);
  }
}

void AddTokenNodes(JToken token, string name, TreeNodeCollection parent)
{
  if (token is JValue)
  {
    parent.Add(new TreeNode(string.Format("{0}: {1}", name, ((JValue)token).Value)));
  }
  else if (token is JArray)
  {
    AddArrayNodes((JArray)token, name, parent);
  }
  else if (token is JObject)
  {
    AddObjectNodes((JObject)token, name, parent);
  }
}
```

That's fine and dandy, but how about adding some unit tests, there are quite lot of code execution paths over there, right? How would you test such a piece of code? The first thing comes to my mind is to not directly code against UI components like ```TreeView``` and ```TreeNodeCollection```, but creating a generic view model and testing that model on your unit tests. This also decouples the design from the UI framework, so that you can easily port your code to another UI framework, say WPF. But still, I couldn't think of an easy and maintainable way to write your assertions. This brings me to this great unit test helper library I have discovered recently, [Approval Tests](http://approvaltests.sourceforge.net/).

Simply put, Approval Tests diffs the results of your test code output with the output you have expected. The comparison can be made on either regular texts or on images if you are testing some UI code. You can watch [Using ApprovalTests in .Net 16 WinForms](https://www.youtube.com/watch?v=hKeKBjoSfJ8) to get the idea. Here is a simple unit test that uses Approval Tests to perform assertion:

```csharp
[Test]
public void Should_load_simple_json()
{
  var treeView = new TreeView
  {
    BorderStyle = BorderStyle.None,
    Width = 400,
    Height = 400,
  };

  LoadJsonToTreeView(treeView, "{ 'foo': 'bar', 'baz': [ 42, 'quux' ] }");
  treeView.ExpandAll();

  WinFormsApprovals.Verify(treeView);
}
```

The test first creates a WinForms ```TreeView``` control and calls the ```LoadJsonToTreeView()``` method (System Under Test) with a simple JSON string. Then ```ExpandAll()``` method is called on ```TreeView``` instance to get all nodes visible, since we are going to take a snapshot on the last line, all nodes better be visible. When you run this unit test for the first time, the test fails and you get the screen shot of the WinForms control that is produced:

![Received Image](/img/p/ApprovalTestsReceived.png "Received Image")

In Approval Tests terms, this is a *received* image which shows the actual output of current unit test code. What you do next is to simply approve this result if that looks correct. To approve a result, you need to create the image of the *expected* result. Approval Tests helps you here by providing the necessary move command on your unit test failure message, something along these lines:

```
move /Y "Test_name.received.png" "Test_name.approved.png"
```

If you also prefer to use ```ClipboardReporter``` as another reporter for your unit tests, this command will automatically copied to your clipboard to be able to quickly run it, which is a great time saver.

Just run this command on your command prompt and the image you have just seen will be renamed and from now on, it will work as the expected result for this unit test. If you change the code and break the unit test, a nice side-by-side comparison view of the actual and expected images will show on a TortoiseDiff window. TortoiseDiff [seems to be a hard dependency of Approval Tests](http://jake.ginnivan.net/getting-started-with-api-approver) and I am not sure how it is discovered on your system or any chance to change it to another diff viewer for that matter.

I have created a [sample project and unit tests project on GitHub](https://github.com/huseyint/JsonTreeView), send me a pull request if I am missing anything :)

PS: I am curious whether Approval Tests handle the different control styles across different operating systems, i.e. you create your approved images on a Windows 8 box but if your build server runs your tests on a Windows Server with classic Windows theme, you will end up having different images. Please let me know if you have any idea about this in comments.