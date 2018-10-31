// Projections
// -----------
var sphericalMercatorProj = new OpenLayers.Projection('EPSG:900913');
var geographicProj = new OpenLayers.Projection('EPSG:4326');

//OpenLayers.Projection.addTransform(sphericalMercatorProj, geographicProj, OpenLayers.Projection.projectInverse);

// Vector layers
// -------------
var p0Layer = new OpenLayers.Layer.Vector(getTitulo("0"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("0"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,	
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
p0Layer.addFeatures(getFeatures("0"));

var p1Layer = new OpenLayers.Layer.Vector(getTitulo("1"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
       externalGraphic: getIcono("1"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,		
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("1")) {
	p1Layer.addFeatures(getFeatures("1"));	
}

var p2Layer = new OpenLayers.Layer.Vector(getTitulo("2"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("2"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("2")) {
	p2Layer.addFeatures(getFeatures("2"));	
}

var p3Layer = new OpenLayers.Layer.Vector(getTitulo("3"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("3"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,	
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("3")) {
	p3Layer.addFeatures(getFeatures("3"));	
}

var p4Layer = new OpenLayers.Layer.Vector(getTitulo("4"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("4"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,	
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("4")) {
	p4Layer.addFeatures(getFeatures("4"));	
}

var p5Layer = new OpenLayers.Layer.Vector(getTitulo("5"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("5"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,	
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("5")) {
	p5Layer.addFeatures(getFeatures("5"));	
}

var p6Layer = new OpenLayers.Layer.Vector(getTitulo("6"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("6"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,	
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("6")) {
	p6Layer.addFeatures(getFeatures("6"));	
}

var p7Layer = new OpenLayers.Layer.Vector(getTitulo("7"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("7"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,	
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("7")) {
	p7Layer.addFeatures(getFeatures("7"));	
}

var p8Layer = new OpenLayers.Layer.Vector(getTitulo("8"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("8"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: false,	
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("8")) {
	p8Layer.addFeatures(getFeatures("8"));	
}

var p9Layer = new OpenLayers.Layer.Vector(getTitulo("9"), {		
	projection: geographicProj,
	styleMap: new OpenLayers.StyleMap({
        externalGraphic: getIcono("9"),
        graphicOpacity: 1.0,
        graphicWith: 16,
        graphicHeight: 26,
        graphicYOffset: -26
    }),  
	visibility: true,	
    preFeatureInsert: function(feature){
        feature.geometry.transform(geographicProj, sphericalMercatorProj);
	}	
});
if (getTitulo("9")) {
	p9Layer.addFeatures(getFeatures("9"));	
}

//buildStyleChooser();

// Create map
// ----------
var map = new OpenLayers.Map({
    div: 'map',
    theme: null,
    projection: sphericalMercatorProj,
    displayProjection: geographicProj,
    units: 'm',
    numZoomLevels: 18,
    maxResolution: 156543.0339,
    maxExtent: new OpenLayers.Bounds(
        -20037508.34, -20037508.34, 20037508.34, 20037508.34
    ),
    controls: [
        new OpenLayers.Control.Attribution(),
        new OpenLayers.Control.Navigation(),
        new OpenLayers.Control.PanZoom(),
        //Si queremos sacar la seleccion de las capas
        //new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher')})
        new OpenLayers.Control.LayerSwitcher()
    ],
    layers: [
        new OpenLayers.Layer.OSM('OpenStreetMap', null, {'displayInLayerSwitcher':false}),
		p9Layer,
		p8Layer,	
		p7Layer,
		p6Layer,
		p5Layer,	
		p4Layer,
		p3Layer,
		p2Layer,
        p1Layer,
		p0Layer
    ],
    center: new OpenLayers.LonLat(-75.5218, 10.4342).transform( geographicProj, sphericalMercatorProj ),
    zoom: 13
});

// Sprinters features
// ------------------
function getFeatures(num) {
	//Tomo la informacion desde la pagina de los mapas
	var desdenum = "desdePHP_" + num;
	var desdephp = document.getElementById(desdenum).innerHTML;
	var features = JSON.parse(desdephp);
    var reader = new OpenLayers.Format.GeoJSON();	
    return reader.read(features);
}

function getTitulo(num) {
	var desdenu = "titulo_" + num;
	var titulo = document.getElementById(desdenu).innerHTML;
	return titulo;	
}

function getIcono(num) {
	var desde = "icon_" + num;
	var icono = document.getElementById(desde).innerHTML;
	return icono;	
}

//--prueba
// add a radio button for each userStyle
function buildStyleChooser() {
    var styles = ["es uno", "es dos"];
    var chooser = document.getElementById("style_chooser"), input, li;
    for (var i=0,ii=styles.length; i<ii; ++i) {
        input = document.createElement("input");
        input.type = "radio";
        input.name = "style";
        input.value = i;
        input.checked = i == 0;
        input.onclick = function() { setStyle(this.value); };
        li = document.createElement("li");
        li.appendChild(input);
        li.appendChild(document.createTextNode(styles[i]));
        chooser.appendChild(li);
    }
}

// set a new style when the radio button changes
function setStyle(index) {
//    waterBodies.styleMap.styles["default"] = sld.namedLayers["WaterBodies"].userStyles[index];
    // apply the new style of the features of the Water Bodies layer
//    waterBodies.redraw();
}


