/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var fccphnc;
var fccpdc;
var fccnc;

//initializes datepicker for 2 date inputs representing start and end dates.
//Logic will ensure that both start and end dates have values when either have been set, and start date <= end date
//Parameters
//dataNode: the object which start and end dates are stored (ie. eccc.castingCall.data)
//startId : the ID of the input element that represent the start dates and end dates
//endId : the ID of the input element that represent the start dates and end dates
//
//opts : Optional settings (object)
//startDataNodeAttribute : the attribute that stores the data value in datanode for start date (*note: if left empty, startDataNodeAttribute = startId)
//endDataNodeAttributes: the attribute that stores the data value in datanode for end date (*note: if left empty, endDataNodeAttribute = endId)
//
//changeCallback: a callback function whenever a change in date input has been triggered
function initDatePeriod(dataNode,startId,endId,opts,changeCallback){
    var startDataNodeAttribute = startId;
    var endDataNodeAttribute = endId;
    if(typeof opts != 'undefined'){
        if(typeof opts.startDataNodeAttribute != 'undefined') startDataNodeAttribute = opts.startDataNodeAttribute;
        if(typeof opts.endDataNodeAttribute != 'undefined') endDataNodeAttribute = opts.endDataNodeAttribute;
    }
    
    //initialize values of start and end date from datanodeattributes
    if(dataNode[startDataNodeAttribute] != null){ 
        var startDate = stringToDate(dataNode[startDataNodeAttribute],'yyyy-mm-dd');
        if(startDate != 'Invalid Date') $('#'+startId).val(dateToString(startDate,'dd/mm/yyyy'));
    }
    
    if(dataNode[endDataNodeAttribute] != null){ 
        var endDate = stringToDate(dataNode[endDataNodeAttribute],'yyyy-mm-dd');
        if(endDate != 'Invalid Date') $('#'+endId).val(dateToString(endDate,'dd/mm/yyyy'));
    }
    
    $('#'+startId).datepicker({
        format : 'dd/mm/yyyy'
    });
    
    $('#'+endId).datepicker({
        format : 'dd/mm/yyyy'
    });
    
    //date logic for audition start and end
    $('#'+startId).change(function(){
        
        if($('#'+startId).val() == ''){
            $('#'+endId).val('');
            dataNode[startDataNodeAttribute] = '';
            dataNode[endDataNodeAttribute] = '';
            return;
        }
        
        var endTime = -1;
        var startDate = stringToDate($('#'+startId).val(),'dd/mm/yyyy');
        if(startDate == 'Invalid Date') {
            $('#'+startId).val('');
            return;
        }
        
        var endDate = startDate;
        
        if($('#'+endId).val() != ''){ 
            endDate = stringToDate($('#'+endId).val(),'dd/mm/yyyy');
            endTime = Date.parse(endDate); 
        }
        
        if(endTime == -1 || Date.parse(startDate) > endTime){
            $('#'+endId).val($('#'+startId).val()).datepicker('update');
        }
            
        dataNode[startDataNodeAttribute] = dateToString(startDate,'yyyy-mm-dd');
        dataNode[endDataNodeAttribute] = dateToString(endDate,'yyyy-mm-dd');
        
        if(changeCallback) changeCallback();
    });
        
    $('#'+endId).change(function(){
        
        if($('#'+endId).val() == ''){
            $('#'+startId).val('');
            dataNode[startDataNodeAttribute] = '';
            dataNode[endDataNodeAttribute] = '';
            return;
        }
        
        var startTime = -1;
        var endDate = stringToDate($('#'+endId).val(),'dd/mm/yyyy');
        if(endDate == 'Invalid Date') endDate = new Date();
        var startDate = endDate;
        
        if($('#'+startId).val() != ''){ 
            startDate = stringToDate($('#'+startId).val(),'dd/mm/yyyy');
            startTime = Date.parse(startDate); 
        }
        if(startTime == -1 || Date.parse(endDate) < startTime){
            $('#'+startId).val($('#'+endId).val()).datepicker('update');
        }
            
        dataNode[startDataNodeAttribute] = dateToString(startDate,'yyyy-mm-dd');
        dataNode[endDataNodeAttribute] = dateToString(endDate,'yyyy-mm-dd');
        
        if(changeCallback) changeCallback();
    });
}

function filter_casting_call_prod_house_name_ctrl($scope){
    fccphnc = $scope;
    
    if(smc.searchQuery.Character.searchProd){
        $scope.prod = smc.searchQuery.Character.searchProd;
    } 
    
    $scope.addProd = function(){
        $scope.prod = $('#fccpn').val();
        if($scope.prod != ""){
            smc.setQuery({
                model : 'Character',
                attribute : 'searchProd',
                value : $scope.prod
            });
        } else {
            smc.removeQuery('Character','searchProd');
        }
    }
    
    
    //Add Production house name if changed
    $('#fccpn').change(function(data){
        fccphnc.addProd(data);
    });
}

function filter_casting_call_prod_date_ctrl($scope){
    fccpdc = $scope;
    $scope.date = {
        start : "",
        end : ""
    };
    
    if(typeof smc.searchQuery.Character.searchDates != 'undefined'){
        $scope.date = smc.searchQuery.Character.searchDates;
        if($scope.date.start) $('#project_start').val($scope.date.start);
        if($scope.date.end) $('#project_end').val($scope.date.end);
    }
    
    initDatePeriod($scope.date,'project_start','project_end',{
        startDataNodeAttribute : 'start',
        endDataNodeAttribute : 'end'
    },function(){fccpdc.addDates()});
   
    $scope.addDates = function(){
        //date logic for project start end
        smc.setQuery({
                model : 'Character',
                attribute : 'searchDates',
                value : $scope.date
            });
    }
}
 
function filter_casting_call_title_ctrl($scope){
    fccnc = $scope;
    
    if(smc.searchQuery.Character.searchCCTitle){
        $scope.title = smc.searchQuery.Character.searchCCTitle;
        
    }
    
    $scope.addTitle = function(){
        smc.setQuery({
                model : 'Character',
                attribute : 'searchCCTitle',
                value : $scope.title
            });
        
    }
}