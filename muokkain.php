<?php
  include 'asetukset.php';

  if ($kirjautunut == TRUE) {

  if ($_GET["sivu"] != "") {
    $haluttusivu = $_GET["sivu"];

    //TODO echo file_exists("test.txt"); tarvitsee clearcachen avuksi
    if (file_exists("osat/" . $haluttusivu) == TRUE) {
      $tiedosto = fopen("osat/" . $haluttusivu, "r");
      $sisältö = fread($tiedosto, filesize("osat/" . $haluttusivu));
      echo $sisältö;
    } else {
      $tiedosto = fopen("sivut/" . $haluttusivu, "r");
      $sisältö = fread($tiedosto, filesize("sivut/" . $haluttusivu));
      echo $sisältö;
    }
  } if ($_GET["sivu"] == "") {

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Muokkain</title>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
  <link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
  <script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
</head>
<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="/"><?php echo $sivunnimi; ?></a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="#">Työpöytä (Dashboard)</a></li>
        <li><a href="muokkain.php">Muokkaa sivua</a></li>
        <li><a href="hallinnoi.php">Hallinnoi sivuja</a></li>
        <li><a href="asetuksetgui.php">Asetukset</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a class="navbar-right" href="kirjaudu.php?kirjaudu=ulos"><span class="glyphicon glyphicon-log-out"></span>Kirjaudu ulos</a></li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <?php
        //Tallentaa jos sisalto POST data löytyy
        if ($_POST["sisalto"] != "") {
          $sisältö = $_POST["sisalto"];
          $kohde = $_POST["kohde"];

          if (file_exists("osat/" . $kohde) == TRUE) {
            $tiedosto = fopen("osat/" . $kohde, "w") or die("Kriittinen virhe!");
            fwrite($tiedosto, $sisältö);
            fclose($tiedosto);
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
              <strong>Tallennettu</strong> kohteeseen <?php echo $kohde; ?>.
            </div>
            <?php
          } else {
            $tiedosto = fopen("sivut/" . $kohde, "w") or die("Kriittinen virhe!");
            fwrite($tiedosto, $sisältö);
            fclose($tiedosto);
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
              <strong>Tallennettu</strong> kohteeseen <?php echo $kohde; ?>.
            </div>
            <?php
          }
        }
    ?>
    <div class="form-group">
      <select class="form-control" id="valittavasivu">
          <?php
          //Etusivun osat
          $arrayetusivuista = scandir("osat");
          foreach ($arrayetusivuista as $key => $value) {
            if ($value != ".") {
              if ($value != "..") {
                echo "<option>" . $value . "</option>";
              }
            }
          }
          echo "<option disabled>──────────</option>";
          //Sivut
          $arraysivuista = scandir("sivut");
          foreach ($arraysivuista as $key => $value) {
            if ($value != ".") {
              if ($value != "..") {
                echo "<option>" . $value . "</option>";
              }
            }
          }
        ?>
      </select>
    </div>
    <?php
    if ($editori === "yksinkertainen") {
     ?>
    <div id="summernote"><h2>Otsikko</h2><p>Voit aloittaa muokkaamisen</p></div>
    <form method="POST" action="" id="lahetyslomake">
      <div class="hiddenit"></div>
      <div class="hiddenit2"></div>
      <input type='submit' class="btn btn-primary" value='Tallenna'>
    </form>
  </div>
  <script>
      var osoitteenalku = location.protocol + "//" + location.hostname + ":" +  location.port +  location.pathname + "?sivu=";
      var kohde = $("#valittavasivu").val();
      $.get(osoitteenalku + kohde, function(data) {
        $('#summernote').summernote('destroy');
        $("#summernote").html("<div id='summernote'>" + data + "</div>");
        $('#summernote').summernote();
      });
      $(".hiddenit2").html("<input type='hidden' name='kohde' value='" + kohde + "''>");


    //Ottaa uuden sisällön editoitavaksi #vallitavasivu:n vaihduttua
    $("#valittavasivu").change(function(){
      var kohde = $("#valittavasivu").val();
      $.get(osoitteenalku + kohde, function(data) {
        $('#summernote').summernote('destroy');
        $("#summernote").html("<div id='summernote'>" + data + "</div>");
        $('#summernote').summernote();
        $(".hiddenit2").html("<input type='hidden' name='kohde' value='" + kohde + "''>");
      });
    });

    function tallennahiddeninputtiin() {
         var hvalue = $('#summernote').summernote('code');
        $(".hiddenit").html("<input type='hidden' name='sisalto' value=' " + hvalue + " '/>");
    }

    setInterval(tallennahiddeninputtiin, 300);
  </script>
  <?php
} else {
  ?>
  <!-- GRAPES JS-->
  <form method="POST" action="" id="lahetyslomake">
    <div class="hiddenit"></div>
    <div class="hiddenit2"></div>
    <input type='submit' class="btn btn-primary" value='Tallenna'>
  </form>
  <hr>
  <link rel="stylesheet" href="//unpkg.com/grapesjs/dist/css/grapes.min.css">
  <script src="//unpkg.com/grapesjs"></script>
  <div id="gjs"></div>
  <script type="text/javascript">
  /*! grapesjs-plugin-bootstrap - 0.1.351 */
!function(e,t){"object"==typeof exports&&"object"==typeof module?module.exports=t(require("grapesjs")):"function"==typeof define&&define.amd?define(["grapesjs"],t):"object"==typeof exports?exports["grapesjs-plugin-bootstrap"]=t(require("grapesjs")):e["grapesjs-plugin-bootstrap"]=t(e.grapesjs)}(this,function(e){return function(e){function t(n){if(a[n])return a[n].exports;var o=a[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,t),o.l=!0,o.exports}var a={};return t.m=e,t.c=a,t.d=function(e,a,n){t.o(e,a)||Object.defineProperty(e,a,{configurable:!1,enumerable:!0,get:n})},t.n=function(e){var a=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(a,"a",a),a},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=1)}([function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});t.capitalize=function(e){return e.charAt(0).toUpperCase()+e.slice(1)},t.getModel=function(e,t){return e.DomComponents.getType(t).model},t.getView=function(e,t){return e.DomComponents.getType(t).view}},function(e,t,a){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var o=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var a=arguments[t];for(var n in a)Object.prototype.hasOwnProperty.call(a,n)&&(e[n]=a[n])}return e},l=a(2),s=n(l),i=a(3),d=n(i),c=a(4),r=n(c),u=a(15),p=n(u),m=a(19),f=n(m),g=a(20),v=n(g);t.default=s.default.plugins.add("grapesjs-plugin-bootstrap",function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=o({},d.default,t);a.addBasicStyle&&e.addComponents('\n      <style>\n        .gjs-dashed .container, .gjs-dashed .container-fluid, .gjs-dashed .row, .gjs-dashed .row > [class*="col-"], .gjs-dashed .dropdown-menu {\n          min-height: 50px;\n        }\n        .gjs-dashed .dropdown-menu {\n          display: block;\n        }\n      </style>\n    '),(0,r.default)(e,a),(0,v.default)(e,a),(0,p.default)(e,a),(0,f.default)(e,a)})},function(t,a){t.exports=e},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={addBasicStyle:!0,blocks:["address","alert","blockquote","button","container","column","columns-2","columns-3","columns-4","columns-4-8","columns-8-4","dropdown","header","image","label","link","list","media","panel","paragraph","row","thumbnail","well"],category:{basics:"Basics",components:"Components",layout:"Layout"}}},function(e,t,a){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var o=a(5),l=n(o),s=a(6),i=n(s),d=a(7),c=n(d),r=a(8),u=n(r),p=a(9),m=n(p),f=a(10),g=n(f),v=a(11),b=n(v),y=a(12),h=n(y),w=a(13),x=n(w),j=a(14),C=n(j);t.default=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};(0,l.default)(e,t),(0,i.default)(e,t),(0,c.default)(e,t),(0,u.default)(e,t),(0,m.default)(e,t),(0,g.default)(e,t),(0,b.default)(e,t),(0,h.default)(e,t),(0,x.default)(e,t),(0,C.default)(e,t)}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a(0);t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("text"),o=a.model,l=a.view,s=["success","info","warning","danger"];t.addType("alert",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Alert",attributes:{role:"alert"},classes:["alert"],traits:o.prototype.defaults.traits.concat([{type:"select-class",label:"Context",options:s.map(function(e){return{value:"alert-"+e,name:(0,n.capitalize)(e)}})}])})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("alert"))return{type:"alert"}}}),view:l})}},function(e,t,a){"use strict";function n(e){if(Array.isArray(e)){for(var t=0,a=Array(e.length);t<e.length;t++)a[t]=e[t];return a}return Array.from(e)}Object.defineProperty(t,"__esModule",{value:!0});var o=a(0);t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=(0,o.getModel)(e,"default"),l=(0,o.getView)(e,"default"),s=(0,o.getModel)(e,"text"),i=(0,o.getView)(e,"text"),d=(0,o.getModel)(e,"image"),c=(0,o.getView)(e,"image"),r=(0,o.getModel)(e,"link"),u=(0,o.getView)(e,"link"),p=["primary","success","info","warning","danger"],m=["left","center","right","justify"],f=["lowercase","uppercase","capitalize"],g=["rounded","circle","thumbnail"],v=[["lg","large"],["sm","small"],["xs","extra small"]];t.addType("default",{model:a.extend({defaults:Object.assign({},a.prototype.defaults,{traits:a.prototype.defaults.traits.concat([{type:"select-class",label:"Float",options:[{value:"",name:"None"},{value:"pull-left",name:"Left"},{value:"pull-right",name:"Right"}]},{type:"select-class",label:"Color",options:[{value:"",name:"None"}].concat(n(["muted"].concat(p).map(function(e){return{value:"text-"+e,name:(0,o.capitalize)(e)}})))},{type:"select-class",label:"Background",options:[{value:"",name:"None"}].concat(n(p.map(function(e){return{value:"bg-"+e,name:(0,o.capitalize)(e)}})))}])})}),view:l}),a=(0,o.getModel)(e,"default"),l=(0,o.getView)(e,"default"),t.addType("image",{model:d.extend({defaults:Object.assign({},d.prototype.defaults,{"custom-name":"Image",attributes:{src:"https://dummyimage.com/450x250/999/222"},traits:[{type:"text",label:"Source (URL)",name:"src"},{type:"text",label:"Alternate text",name:"alt"},{type:"select-class",label:"Responsive",options:[{value:"",name:"No"},{value:"img-responsive",name:"Yes"}]},{type:"select-class",label:"Shape",options:[{value:"",name:"none"}].concat(n(g.map(function(e){return{value:"img-"+e,name:(0,o.capitalize)(e)}})))}]})},{isComponent:function(e){if(e&&e.tagName&&"IMG"===e.tagName)return{type:"image"}}}),view:c}),t.addType("text",{model:a.extend({defaults:Object.assign({},s.prototype.defaults,{droppable:!0,traits:a.prototype.defaults.traits.concat([{type:"select-class",label:"Alignment",options:[].concat(n(m.map(function(e){return{value:"text-"+e,name:(0,o.capitalize)(e)}})),[{value:"text-nowrap",name:"No wrap"}])},{type:"select-class",label:"Transform",options:[{value:"",name:"None"}].concat(n(f.map(function(e){return{value:"text-"+e,name:(0,o.capitalize)(e)}})))}])})}),view:i}),s=(0,o.getModel)(e,"text"),i=(0,o.getView)(e,"text"),t.addType("header",{model:s.extend({defaults:Object.assign({},s.prototype.defaults,{"custom-name":"Header",tagName:"h1",traits:s.prototype.defaults.traits.concat([{type:"select",options:[{value:"h1",name:"One (largest)"},{value:"h2",name:"Two"},{value:"h3",name:"Three"},{value:"h4",name:"Four"},{value:"h5",name:"Five"},{value:"h6",name:"Six (smallest)"}],label:"Size",name:"tagName",changeProp:1}])})},{isComponent:function(e){if(e&&e.tagName&&["H1","H2","H3","H4","H5","H6"].includes(e.tagName))return{type:"header"}}}),view:i}),t.addType("link",{model:r.extend({defaults:Object.assign({},r.prototype.defaults,{traits:[{type:"text",name:"id",label:"Id",placeholder:"eg. Text here"}].concat(r.prototype.defaults.traits,[{type:"select",label:"Toggles",name:"data-toggle",options:[{value:"",name:"None"},{value:"button",name:"Self"},{value:"collapse",name:"Collapse"},{value:"dropdown",name:"Dropdown"}],changeProp:1}])})}),view:u}),r=(0,o.getModel)(e,"link"),u=(0,o.getView)(e,"link"),t.addType("button",{model:r.extend({defaults:Object.assign({},r.prototype.defaults,{"custom-name":"Button",attributes:{role:"button"},classes:["btn"],traits:r.prototype.defaults.traits.concat([{type:"select-class",label:"Context",options:[{value:"btn-default",name:"Default"}].concat(n(p.map(function(e){return{value:"btn-"+e,name:(0,o.capitalize)(e)}})))},{type:"select-class",label:"Size",options:[{value:"",name:"Default"}].concat(n(v.map(function(e){return{value:"btn-"+e[0],name:(0,o.capitalize)(e[1])}})))},{type:"select-class",label:"Width",options:[{value:"",name:"Inline"},{value:"btn-block",name:"Block"}]}])},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("btn"))return{type:"button"}}})}),view:u}),t.addType("list",{model:a.extend({defaults:Object.assign({},a.prototype.defaults,{"custom-name":"List",droppable:!0,traits:a.prototype.defaults.traits.concat([{type:"select",label:"Type",name:"tagName",options:[{value:"ul",name:"Unordered"},{value:"ol",name:"Ordered"}],changeProp:1},{type:"select-class",label:"Style",options:[{value:"",name:"none"},{value:"list-unstyled",name:"Unstyled"},{value:"list-inline",name:"Inline"}]}])})},{isComponent:function(e){if(e&&e.tagName&&("UL"===e.tagName||"OL"===e.tagName))return{type:"list"}}}),view:l}),t.addType("list-item",{model:s.extend({defaults:Object.assign({},s.prototype.defaults,{"custom-name":"Item",tagName:"li",draggable:"ul, ol"})},{isComponent:function(e){if(e&&e.tagName&&"LI"===e.tagName)return{type:"list-item"}}}),view:i}),t.addType("paragraph",{model:s.extend({defaults:Object.assign({},s.prototype.defaults,{"custom-name":"Paragraph",tagName:"p",traits:s.prototype.defaults.traits.concat([{type:"select-class",label:"Lead",options:[{value:"",name:"No"},{value:"lead",name:"Yes"}]}])})},{isComponent:function(e){if(e&&e.tagName&&"P"===e.tagName)return{type:"paragraph"}}}),view:i}),t.addType("blockquote",{model:a.extend({defaults:Object.assign({},a.prototype.defaults,{tagName:"blockquote",traits:a.prototype.defaults.traits.concat([{type:"select-class",label:"Reversed",options:[{value:"",name:"No"},{value:"blockquote-reverse",name:"Yes"}]}])})},{isComponent:function(e){if(e&&e.tagName&&"BLOCKQUOTE"===e.tagName)return{type:"blockquote"}}}),view:l})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("default"),n=a.model,o=a.view;t.addType("container",{model:n.extend({defaults:Object.assign({},n.prototype.defaults,{"custom-name":"Container",tagName:"div",droppable:!0,traits:n.prototype.defaults.traits.concat([{type:"select-class",label:"Type",options:[{value:"container",name:"Fixed"},{value:"container-fluid",name:"Fluid"}]}])})},{isComponent:function(e){if(e&&e.classList&&(e.classList.contains("container")||e.classList.contains("container-fluid")))return{type:"container"}}}),view:o})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("default"),n=a.model,o=a.view,l=t.getType("text"),s=l.model,i=l.view,d=t.getType("link"),c=d.model,r=t.getType("list-item"),u=r.model,p=r.view;t.addType("dropdown",{model:n.extend({defaults:Object.assign({},n.prototype.defaults,{"custom-name":"Dropdown",droppable:"a, button, .dropdown-menu",traits:n.prototype.defaults.traits.concat([{type:"select-class",label:"State",options:[{value:"",name:"Closed"},{value:"open",name:"Open"}]}])})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("dropdown"))return{type:"dropdown"}}}),view:o}),t.addType("dropdown-toggle",{model:s.extend({defaults:Object.assign({},s.prototype.defaults,{"custom-name":"Dropdown Toggle",draggable:".dropdown",droppable:!0,traits:[{type:"checkbox",name:"aria-haspopup",label:"Popup"},{type:"checkbox",name:"aria-expanded",label:"Expanded"},{type:"select",label:"Type",name:"tagName",options:[{value:"button",name:"Button"},{value:"a",name:"Link"}],changeProp:1}]}),init:function(){this.listenTo(this,"change:tagName",this.changeTag)},changeTag:function(e){var t=this.get("attributes"),a=this.get("traits"),n=["tagName","aria-haspopup","aria-expanded"];a.models=a.models.filter(function(e){return n.indexOf(e.get("name"))>=0}),"a"===this.get("tagName")?a.add(c.prototype.defaults.traits):t.href&&delete t.href,this.set("attributes",Object.assign({},t)),this.sm.trigger("change:selectedComponent")}},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("dropdown-toggle"))return{type:"dropdown-toggle"}}}),view:i}),t.addType("dropdown-menu",{model:n.extend({defaults:Object.assign({},n.prototype.defaults,{"custom-name":"Dropdown Menu",draggable:".dropdown, .btn-group",droppable:"li"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("dropdown-menu"))return{type:"dropdown-menu"}}}),view:o}),t.addType("dropdown-item",{model:u.extend({defaults:Object.assign({},u.prototype.defaults,{"custom-name":"Dropdown Item",draggable:".dropdown-menu"})},{isComponent:function(e){var t=e.parentNode;if(t&&t.classList&&t.classList.contains("dropdown-menu"))return{type:"dropdown-item"}}}),view:p})}},function(e,t,a){"use strict";function n(e){if(Array.isArray(e)){for(var t=0,a=Array(e.length);t<e.length;t++)a[t]=e[t];return a}return Array.from(e)}Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("default"),o=a.model,l=a.view,s=[1,2,3,4,5,6,7,8,9,10,11,12];t.addType("row",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Row",tagName:"div",draggable:".container, .container-fluid",droppable:'[class*="col-xs"], [class*="col-sm"], [class*="col-md"], [class*="col-lg"]'})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("row"))return{type:"row"}}}),view:l}),t.addType("column",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Column",draggable:".row",droppable:!0,traits:o.prototype.defaults.traits.concat([{type:"select-class",options:[{value:"",name:"None"}].concat(n(s.map(function(e){return{value:"col-xs-"+e,name:e+"/12"}}))),label:"XS size"},{type:"select-class",options:[{value:"",name:"None"}].concat(n(s.map(function(e){return{value:"col-sm-"+e,name:e+"/12"}}))),label:"SM size"},{type:"select-class",options:[{value:"",name:"None"}].concat(n(s.map(function(e){return{value:"col-md-"+e,name:e+"/12"}}))),label:"MD size"},{type:"select-class",options:[{value:"",name:"None"}].concat(n(s.map(function(e){return{value:"col-lg-"+e,name:e+"/12"}}))),label:"LG size"},{type:"select-class",options:[{value:"",name:"None"}].concat(n(s.map(function(e){return{value:"col-xs-offset-"+e,name:e+"/12"}}))),label:"XS offset"},{type:"select-class",options:[{value:"",name:"None"}].concat(n(s.map(function(e){return{value:"col-sm-offset-"+e,name:e+"/12"}}))),label:"SM offset"},{type:"select-class",options:[{value:"",name:"None"}].concat(n(s.map(function(e){return{value:"col-md-offset-"+e,name:e+"/12"}}))),label:"MD offset"},{type:"select-class",options:[{value:"",name:"None"}].concat(n(s.map(function(e){return{value:"col-lg-offset-"+e,name:e+"/12"}}))),label:"LG offset"}])})},{isComponent:function(e){if(e&&e.className&&e.className.match(/col-(xs|sm|md|lg)-\d+/))return{type:"column"}}}),view:l})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a(0);t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("text"),o=a.model,l=a.view,s=["default","primary","success","info","warning","danger"];t.addType("label",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Label",classes:["label"],traits:[{type:"select-class",label:"Context",options:s.map(function(e){return{value:"label-"+e,name:(0,n.capitalize)(e)}})}]})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("label"))return{type:"label"}}}),view:l})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a(0);t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("default"),o=a.model,l=a.view,s=t.getType("header"),i=s.model,d=s.view,c=["left","right"],r=["top","middle","bottom"];t.addType("media",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Media",classes:["media"],droppable:".media-left, .media-right, .media-body"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("media"))return{type:"media"}}}),view:l}),t.addType("media-side",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Media Side",draggable:".media",traits:[{type:"select-class",label:"Side",options:c.map(function(e){return{value:"media-"+e,name:(0,n.capitalize)(e)}})},{type:"select-class",label:"Position",options:r.map(function(e){return{value:"media-"+e,name:(0,n.capitalize)(e)}})}]})},{isComponent:function(e){if(e&&e.className&&e.className.match(/media-(left|right)/))return{type:"media-side"}}}),view:l}),t.addType("media-body",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Media Body",draggable:".media"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("media-body"))return{type:"media-body"}}}),view:l}),t.addType("media-heading",{model:i.extend({defaults:Object.assign({},i.prototype.defaults,{"custom-name":"Media Heading",draggable:".media-body"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("media-heading"))return{type:"media-heading"}}}),view:d})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a(0);t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("default"),o=a.model,l=a.view,s=t.getType("header"),i=s.model,d=s.view,c=["default","primary","success","info","warning","danger"];t.addType("panel",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Panel",droppable:".panel-heading, .panel-body, .panel-footer, .table, .list-group",traits:o.prototype.defaults.traits.concat([{type:"select-class",label:"Context",name:"context",options:c.map(function(e){return{value:"panel-"+e,name:(0,n.capitalize)(e)}})}])})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("panel"))return{type:"panel"}}}),view:l}),t.addType("panel-body",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Panel Body",draggable:".panel"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("panel-body"))return{type:"panel-body"}}}),view:l}),t.addType("panel-footer",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Panel Footer",draggable:".panel"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("panel-footer"))return{type:"panel-footer"}}}),view:l}),t.addType("panel-heading",{model:o.extend({defaults:Object.assign({},o.prototype.defaults,{"custom-name":"Panel Heading",draggable:".panel"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("panel-heading"))return{type:"panel-heading"}}}),view:l}),t.addType("panel-title",{model:i.extend({defaults:Object.assign({},i.prototype.defaults,{"custom-name":"Panel Title",draggable:".panel-heading"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("panel-title"))return{type:"panel-title"}}}),view:d})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("default"),n=a.model,o=a.view;t.addType("thumbnail",{model:n.extend({defaults:Object.assign({},n.prototype.defaults,{"custom-name":"Thumbnail",droppable:"img, .caption"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("thumbnail"))return{type:"thumbnail"}}}),view:o}),t.addType("thumbnail-caption",{model:n.extend({defaults:Object.assign({},n.prototype.defaults,{"custom-name":"Thumbnail Caption",draggable:".thumbnail"})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("caption")&&e.parentNode.classList.contains("thumbnail"))return{type:"thumbnail-caption"}}}),view:o})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DomComponents),a=t.getType("default"),n=a.model,o=a.view;t.addType("well",{model:n.extend({defaults:Object.assign({},n.prototype.defaults,{"custom-name":"Well",traits:n.prototype.defaults.traits.concat([{type:"select-class",label:"Size",name:"size",options:[{value:"",name:"Default"},{value:"well-sm",name:"Small"},{value:"well-lg",name:"Large"}]}])})},{isComponent:function(e){if(e&&e.classList&&e.classList.contains("well"))return{type:"well"}}}),view:o})}},function(e,t,a){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var o=a(16),l=n(o),s=a(17),i=n(s),d=a(18),c=n(d);t.default=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};(0,l.default)(e,t),(0,i.default)(e,t),(0,c.default)(e,t)}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=e.BlockManager,n=t.blocks,o=t.category,l=o.basics,s=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return n.indexOf(e)>=0?a.add(e,t):null};s("address",{label:"Address",category:l,attributes:{class:"fa fa-location-arrow"},content:"\n      <address>\n        <strong>Twitter, Inc.</strong><br>\n        1355 Market Street, Suite 900<br>\n        San Francisco, CA 94103<br>\n        Phone: (123) 456-7890\n      </address>\n    "}),s("blockquote",{label:"Blockquote",category:l,attributes:{class:"fa fa-quote-left"},content:'\n      <blockquote>\n        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>\n        <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>\n      </blockquote>\n    '}),s("button",{label:"Button",category:l,attributes:{class:"fa fa-link"},content:{type:"button",content:"Button"}}),s("header",{label:"Header",category:l,attributes:{class:"fa fa-header"},content:{type:"header",content:"Insert your header text here"}}),s("image",{label:"Image",category:l,attributes:{class:"fa fa-picture-o"},content:{type:"image"}}),s("link",{label:"Link",category:l,attributes:{class:"fa fa-link"},content:{type:"link",content:"Link"}}),s("list",{label:"List",category:l,attributes:{class:"fa fa-list"},content:"\n      <ul>\n        <li>Item 1</li>\n        <li>Item 2</li>\n        <li>Item 3</li>\n      </ul>\n    "}),s("paragraph",{label:"Paragraph",category:l,attributes:{class:"fa fa-align-left"},content:{type:"paragraph",content:"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt."}})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=e.BlockManager,n=t.blocks,o=t.category,l=o.components,s=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return n.indexOf(e)>=0?a.add(e,t):null};s("alert",{label:"Alert",category:l,attributes:{class:"fa fa-exclamation-triangle"},content:'\n      <div class="alert alert-success" role="alert">\n        <strong>Well done!</strong> You successfully read this important alert message.\n      </div>\n    '}),s("dropdown",{label:"Dropdown",category:l,attributes:{class:"fa fa-caret-square-o-down"},content:'\n      <div class="dropdown">\n        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n          <span> Dropdown </span>\n          <span class="caret"></span>\n        </button>\n        <ul class="dropdown-menu">\n          <li><a href="#">Item 1</a></li>\n          <li><a href="#">Item 2</a></li>\n          <li><a href="#">Item 3</a></li>\n        </ul>\n      </div>\n    '}),s("media",{label:"Media",category:l,content:'\n      <div class="media">\n        <div class="media-left media-top">\n          <a href="#">\n            <img class="media-object" src="https://dummyimage.com/450x250/999/222" alt="..."/>\n          </a>\n        </div>\n        <div class="media-body">\n          <h4 class="media-heading">Top aligned media</h4>\n          <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>\n        </div>\n      </div>\n    '}),s("panel",{label:"Panel",category:l,attributes:{class:"fa fa-window-maximize"},content:'\n      <div class="panel panel-default">\n        <div class="panel-heading">\n          <h3 class="panel-title">Heading</h3>\n        </div>\n        <div class="panel-body">\n          <p>Content</p>\n        </div>\n        <div class="panel-footer">\n          <p>Footer</p>\n        </div>\n      </div>\n    '}),s("thumbnail",{label:"Thumbnail",category:l,content:'\n      <div class="thumbnail">\n        <img src="https://dummyimage.com/450x250/999/222" alt=""/>\n        <div class="caption">\n          <h3>Thumbnail label</h3>\n          <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>\n          <p><a href="#" class="btn btn-primary" role="button">Button</a></p>\n        </div>\n      </div>\n    '}),s("well",{label:"Well",category:l,attributes:{class:"fa fa-square"},content:'\n      <div class="well">\n        <p>Content</p>\n      </div>\n    '})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=e.BlockManager,n=t.blocks,o=t.category,l=o.layout,s=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return n.indexOf(e)>=0?a.add(e,t):null};s("container",{label:"Container",category:l,attributes:{class:"fa fa-square-o"},content:{type:"container",classes:["container"]}}),s("row",{label:"Row",category:l,attributes:{class:"fa fa-minus"},content:{type:"row",classes:["row"]}}),s("column",{label:"Column",category:l,attributes:{class:"fa fa-columns"},content:{type:"column",classes:["col-md-12"]}}),s("columns-2",{label:"2 Columns",category:l,attributes:{class:"fa fa-columns"},content:'\n      <div class="row">\n        <div class="col-md-6"></div>\n        <div class="col-md-6"></div>\n      </div>\n    '}),s("columns-3",{label:"3 Columns",category:l,attributes:{class:"fa fa-columns"},content:'\n      <div class="row">\n        <div class="col-md-4"></div>\n        <div class="col-md-4"></div>\n        <div class="col-md-4"></div>\n      </div>\n    '}),s("columns-4",{label:"4 Columns",category:l,attributes:{class:"fa fa-columns"},content:'\n      <div class="row">\n        <div class="col-md-3"></div>\n        <div class="col-md-3"></div>\n        <div class="col-md-3"></div>\n        <div class="col-md-3"></div>\n      </div>\n    '}),s("columns-4-8",{label:"2 Columns 4/8",category:l,attributes:{class:"fa fa-columns"},content:'\n      <div class="row">\n        <div class="col-md-4"></div>\n        <div class="col-md-8"></div>\n      </div>\n    '}),s("columns-8-4",{label:"2 Columns 8/4",category:l,attributes:{class:"fa fa-columns"},content:'\n      <div class="row">\n        <div class="col-md-8"></div>\n        <div class="col-md-4"></div>\n      </div>\n    '})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=(arguments.length>1&&void 0!==arguments[1]&&arguments[1],e.DeviceManager);t.add("Extra Small","575px"),t.add("Small","767px"),t.add("Medium","991px"),t.add("Large","1199px"),t.add("Extra Large","100%");var a=e.Panels,n=e.Commands;a.addPanel({id:"devices-buttons"}).get("buttons").add([{id:"deviceXl",command:"set-device-xl",className:"fa fa-desktop",text:"XL",attributes:{title:"Extra Large"},active:1},{id:"deviceLg",command:"set-device-lg",className:"fa fa-desktop",attributes:{title:"Large"}},{id:"deviceMd",command:"set-device-md",className:"fa fa-tablet",attributes:{title:"Medium"}},{id:"deviceSm",command:"set-device-sm",className:"fa fa-mobile",attributes:{title:"Small"}},{id:"deviceXs",command:"set-device-xs",className:"fa fa-mobile",attributes:{title:"Extra Small"}}]),n.add("set-device-xs",{run:function(e){e.setDevice("Extra Small")}}),n.add("set-device-sm",{run:function(e){e.setDevice("Small")}}),n.add("set-device-md",{run:function(e){e.setDevice("Medium")}}),n.add("set-device-lg",{run:function(e){e.setDevice("Large")}}),n.add("set-device-xl",{run:function(e){e.setDevice("Extra Large")}})}},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};(0,o.default)(e,t)};var n=a(21),o=function(e){return e&&e.__esModule?e:{default:e}}(n)},function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){arguments.length>1&&void 0!==arguments[1]&&arguments[1];e.TraitManager.addType("select-class",{events:{change:"onChange"},onValueChange:function(){for(var e=this.model.get("options").map(function(e){return e.value}),t=0;t<e.length;t++)if(e[t].length>0)for(var a=e[t].split(" "),n=0;n<a.length;n++)a[n].length>0&&this.target.removeClass(a[n]);var o=this.model.get("value");if(o.length>0&&"GJS_NO_CLASS"!==o)for(var l=o.split(" "),s=0;s<l.length;s++)this.target.addClass(l[s]);this.target.em.trigger("change:selectedComponent")},getInputEl:function(){if(!this.inputEl){for(var e=this.model,t=e.get("options")||[],a=document.createElement("select"),n=this.target.view.el,o=0;o<t.length;o++){var l=t[o].name,s=t[o].value;""===s&&(s="GJS_NO_CLASS");var i=document.createElement("option");i.text=l,i.value=s,n.classList.contains(s)&&i.setAttribute("selected","selected"),a.append(i)}this.inputEl=a}return this.inputEl}})}}])});
    var editor = grapesjs.init({
        container : '#gjs',
        components: '<div class="txt-red">Hei maailma!</div>',
        plugins: ['grapesjs-plugin-bootstrap'],
        pluginsOpts: {
          'grapesjs-plugin-bootstrap': {
            // options
          }
        }
    });

    /* Omat (tällä hetkellä käytössä plugin)
    var blockManager = editor.BlockManager;

    blockManager.add('my-map-block', {
      label: 'Yksinkertainen kartta',
      content: {
        type: 'map', // Built-in 'map' component
        style: {
          height: '350px'
        },
        removable: true, // Once inserted it can't be removed
      }
    })

    blockManager.add('h1-block', {
      label: 'Otsikko H1',
      content: '<h1>Otsikko tähän...</h1>',
      category: 'Basic',
      attributes: {
        title: 'Lisää H1-palikka'
      }
    });

    blockManager.add('h2-block', {
      label: 'Otsikko H2',
      content: '<h2>Otsikko tähän...</h2>',
      category: 'Basic',
      attributes: {
        title: 'Lisää H2-palikka'
      }
    });

    blockManager.add('the-row-block', {
      label: '2 puolta',
      content: `
      <div class="row" data-gjs-droppable=".row-cell" data-gjs-custom-name="Row">
        <div class="row-cell" data-gjs-draggable=".row"></div>
        <div class="row-cell" data-gjs-draggable=".row"></div>
      </div>
      <style>
        .row {
          display: flex;
          justify-content: flex-start;
          align-items: stretch;
          flex-wrap: nowrap;
          padding: 10px;
          min-height: 75px;
        }
        .row-cell {
          flex-grow: 1;
          flex-basis: 100%;
          padding: 5px;
        }
      </style>
    `,
    })

    blockManager.add('table', {
      label: 'Taulukko',
      content: `
      <table class="table">
        <thead>
          <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>John</td>
            <td>Doe</td>
            <td>john@example.com</td>
          </tr>
          <tr>
            <td>Mary</td>
            <td>Moe</td>
            <td>mary@example.com</td>
          </tr>
          <tr>
            <td>July</td>
            <td>Dooley</td>
            <td>july@example.com</td>
          </tr>
        </tbody>
      </table>
    `,
    })

    blockManager.add('alue', {
      label: 'Alue',
      content: `
      <div class="container">
        <h2>Alue</h2>
        <p>Lorem ipsum...</p>
      </div>
    `
    });

    blockManager.add('horisontaalinen-viiva', {
      label: 'Horisontaalinen viiva',
      content: `
      <hr>
    `
    });

    blockManager.add('lista', {
      label: 'Lista',
      content: `
      <ul class="list-group">
        <li class="list-group-item">First item</li>
        <li class="list-group-item">Second item</li>
        <li class="list-group-item">Third item</li>
      </ul>
    `
    });

    blockManager.add('navigaatio', {
      label: 'Navigaatio-palkki',
      content: `
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">WebSiteName</a>
          </div>
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Page 1</a></li>
            <li><a href="#">Page 2</a></li>
            <li><a href="#">Page 3</a></li>
          </ul>
        </div>
      </nav>
    `
    });

    */

  function vaihda(data) {
    editor.DomComponents.clear(); // Clear components
    editor.CssComposer.clear();  // Clear styles
    editor.UndoManager.clear();
    editor.getComponents().add(data);
  }


  var osoitteenalku = location.protocol + "//" + location.hostname + ":" +  location.port +  location.pathname + "?sivu=";
  var kohde = $("#valittavasivu").val();

  $.get(osoitteenalku + kohde, function(data) {
    vaihda(data);
  });

  $(".hiddenit2").html("<input type='hidden' name='kohde' value='" + kohde + "''>");


  //Ottaa uuden sisällön editoitavaksi #vallitavasivu:n vaihduttua
  $("#valittavasivu").change(function(){
    var kohde = $("#valittavasivu").val();
    $.get(osoitteenalku + kohde, function(data) {
      vaihda(data);
      $(".hiddenit2").html("<input type='hidden' name='kohde' value='" + kohde + "''>");
    });
  });

  function tallennahiddeninputtiin() {
      var Html = editor.getHtml();
      $(".hiddenit").html("<input type='hidden' name='sisalto' value=' " + Html + " '/>");
  }

  setInterval(tallennahiddeninputtiin, 300);
  </script>
  <?php
}
   ?>
</body>
</html>
<?php
  }
} else {
  ?>
  <a href="kirjaudu.php">Kirjaudu</a>
  <?php
}

   ?>
