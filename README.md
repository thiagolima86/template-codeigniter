# Template Codeigniter
This is a template library to codeigniter. Like Laravel blade, but is not equal.

##Install
Put View.php into library folder. Call the library in config/autoload.php. 

##Using
In controller method, write ```$this->view->get("myview", array('var' => 'ok'));```

###View
Create a file called myview.php into view folder.

You start including your layout template with ```@layout(template/main)```

You can send variables to layout. Use @session(var:"value"). Example: ```@session(title:"exemple.com")``` 

How declare you a variable? ```@var{teste:"hello")```

Print variable ```{{$teste}}``` This return: hello

If sintax:
```
@if(1==1)
{{$teste}}
@else
<p>hello world</p>
@endif
```
This return: hello
You can use ```@foreach() ... @endforach````and you can use too while, for.

Exemple:

```
@layout(template/main)
@session(title:"exemple.com")
@var{h1:"Hello World")

<h1>{{$h1}}</h1>
...

```

###Layout

In "template/main.php" into view folder.
see a exemple:

```
<!document html>
<html>
  <head>
    <title>{{$title}}</title>
  </head>
  <body>
    @content
  </body>
</html>
```
The ```@content``` Call the content view myview.
