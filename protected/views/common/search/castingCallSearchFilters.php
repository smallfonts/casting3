<script>
    
    function ccsfInit(callback){
        $('#c3_ccsf').hide();
        ccsfScriptLoader = new c3ScriptLoader();
        ccsfScriptLoader.addJavascript(baseUrl+'/js/controllers/common/search/filter_casting_call_ctrls.js');
        ccsfScriptLoader.addJavascript(baseUrl+'/js/lib/datepicker/bootstrap-datepicker.js');
        ccsfScriptLoader.addStylesheet(baseUrl+'/js/lib/datepicker/datepicker.css');
        ccsfScriptLoader.load(function(){
            angular.bootstrap($('#c3_ccsf'), []);
            $('#c3_ccsf').show();
        });
    }
    
</script>


<span id="c3_ccsf" class="span12">
    <div ng-controller="filter_casting_call_prod_house_name_ctrl">
        <h6>Production House</h6>
        <input id="fccpn" style="width:95%" placeholder="Name" type="text" ng-model="prod"  ng-change="addProd()">    
    </div>
    <hr class="c3-line-condensed"/>
    <div ng-controller="filter_casting_call_title_ctrl">
        <h6>Title of Casting Call</h6>                  
        <input id="cc_title" style="width:95%" placeholder="Casting Call Title" type="text" ng-model="title" ng-change="addTitle()" >
    </div>
    
    <hr class="c3-line-condensed"/>
    
    <div ng-controller="filter_casting_call_prod_date_ctrl">
        <h6>Production Duration</h6>
        <table>
            <tr><td><input style="width:72px;" id="project_start" class="c3datepicker" size="14" type="text" placeholder="dd/mm/yyyy" type="text" ></td>
                <td style="text-align:center;vertical-align:middle;padding:5px"><h6>to</h6></td>
                <td><input style="width:72px;" id="project_end" class="c3datepicker" size="14" type="text" placeholder="dd/mm/yyyy" type="text"></td></tr>
        </table>
    </div>
    <hr class="c3-line-condensed"/>
</span>