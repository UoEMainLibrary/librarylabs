<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="stylesheet" type="text/css" href="build/mirador/css/mirador-combined.min.css">
  <title>Mirador Viewer</title>
  <style type="text/css">
    body { padding: 0; margin: 0; overflow: hidden; font-size: 70%; }
    #viewer { background: #333 url(images/debut_dark.png) left top repeat; width: 100%; height: 100%; position: fixed; }
  </style>
</head>
<body>

<div id="viewer"></div>

<script src="build/mirador/mirador.js"></script>
<script type="text/javascript">


$(function () {
  Mirador({
          "id": "viewer",
          "currentWorkspaceType": "singleObject",
          "saveSession": false,
          "layout": "1x1",
          "data": [
          {"manifestUri": "https://view.nls.uk/manifest/8152/81522537/manifest.json"}
          ],
          "windowObjects": [
          {
          "loadedManifest": "https://view.nls.uk/manifest/8152/81522537/manifest.json",
          "viewType": "ImageView"
          }
          ]
          });
  });



  </script>
</body>
</html>
