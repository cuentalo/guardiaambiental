// Create control and add some layers 
// ----------------------------------
var fpControl = new OpenLayers.Control.FeaturePopups({
    // External div for list popups
    popupOptions: {
        list: {
            // Uses an existing div having an id 'divList'
            popupClass: 'divList'
        },
        single: null // Show a list instead of single popup if the list
                     //     has only an item.
    },
    boxSelectionOptions: {},
    layers: [
        [
        // Uses: Templates for hover & select and safe selection
        p3Layer, {templates: {
            // hover: single & list
            hover: '${.name}',
            hoverList: '<b>${count}</b><br>${html}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
        }}], [
        // Uses: Internationalized templates.
        p1Layer, {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
        }}], [
        // Uses: Templates as functions (only from hover-single and select-list)
        p4Layer, {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
		}}]
    ]
});
map.addControl(fpControl);

// Add a layer to the control using addLayer
// -----------------------------------------
fpControl.addLayer(
    p2Layer,
    {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
    }}
);
fpControl.addLayer(
    p0Layer,
    {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
    }}
);

fpControl.addLayer(
    p5Layer,
    {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
    }}
);

fpControl.addLayer(
    p6Layer,
    {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
    }}
);
fpControl.addLayer(
    p7Layer,
    {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
    }}
);

fpControl.addLayer(
    p8Layer,
    {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
    }}
);

fpControl.addLayer(
    p9Layer,
    {templates: {
            hover: '${.name}<br><div>${.description}</div>',
            hoverList: '<b>${count}</b><br>${.description}',
            hoverItem: '${.name}<br>',
            // select: single & list
            single: '<div><h2>${.name}</h2>${.description}</div>',
            item: '<li><div><a href="#" ${showPopup()}>${.name}</a></div><div>${.description}</div></li>'
    }}
);