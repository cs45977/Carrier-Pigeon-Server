
<script type="text/javascript">

    $(document).ready(function() {
	$('#<?= $table_id ?>').dataTable( {
            
            "bStateSave": true,
            "fnServerData": function ( sSource, aoData, fnCallback ) {
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
            "fnCookieCallback": function (sName, oData, sExpires, sPath) {
                //console.log(oData);
                return sName + "="+JSON.stringify(oData)+"; expires=" + sExpires +"; path=" + sPath;
            },
            "fnDrawCallback": function () {
                //alert("called right before the new draw...");
            },
            "fnStateSaveCallback": function (oSettings, sValue) {
                update_hash(sValue);
                return sValue;
            }
	} );
    } );

    $(window).bind("hashchange", function() {
        //alert(window.location.hash);
    });

    function poo() {
        //my_meeeep = null;
    }

    function update_hash(sValue) {
        var sValue = JSON.parse( sValue+"}" );
        console.log(sValue);
        window.location.hash = "#top?start="+sValue.iStart+"length="+sValue.iLength;
    }

</script>

