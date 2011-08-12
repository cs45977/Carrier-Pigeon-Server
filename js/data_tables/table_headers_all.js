/* 
 * This sets up our table headers 
 */
jQuery(document).ready(function(){
	jQuery('#usersList').dataTable( {
        
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
		"aoColumns": [
            /* key_id */                    { "bSearchable": false,   "bVisible":    false },
            /* user_id */                   { "bSearchable": false,   "bVisible":    false },
            /* first_name */                { "bSearchable": true,   "bVisible":    true },
            /* last_name */                 { "bSearchable": true,   "bVisible":    true },
            /* username */                  { "bSearchable": true,   "bVisible":    true },
            /* key */                       { "bSearchable": true,   "bVisible":    true },
            /* level */                     { "bSearchable": true,   "bVisible":    true },
            /* date_created */              { "bSearchable": true,   "bVisible":    true }
		] } );

});

