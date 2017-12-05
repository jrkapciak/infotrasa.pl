$(document).ready(function()
    {
        $.ajax({
            type: "GET",
            url: "utrudnienia.xml",
            dataType: "xml",
            success: parseXml
            });
    });
function parseXml(xml)
    { 
        $(xml).find("point").each(function()
        {
            $(this).attr("name")
            $(this).find("lat").text()
            $("#output").append("Name: " + $(this).attr("name") + "<br />");
            $("#output").append("Latitude: " + $(this).find("lat").text() + "<br />");
            mapPoint=
            L.marker([$(this).find("lat").text(),$(this).find("long").text()]).addTo(map);
            mapPoint.bindPopup($(this).attr("name"));

        });
    }