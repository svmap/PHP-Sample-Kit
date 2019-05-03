"use strict"

function submit_form(url,data_array)
{
    console.log(url);
    return $.ajax
    ({
        "type": "post",
        "data": data_array,
        "url": url,
        // "contentType": false,
        // "cache": false,
        "async": !1,
        // "dataType" : "json",
        // "processData": false,
        beforeSend: function()
        {
            $('body').click(false);
            // document.getElementById("loader1").style.display = "block";
        },
        complete: function()
        {
           // document.getElementById("loader1").style.display = "none";
        },
        success: function(data)
        {
            //console.log(data);
        }
    });
}
    