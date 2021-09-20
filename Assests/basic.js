function showDialog(time) {

    window.location = '../../formsubmition/?time='+time;
    }

$(document).ready(function() {
var calendar = $('#calendar').fullCalendar({
defaultView: 'agendaWeek',
minTime: "08:00:00",
maxTime: "20:00:00",
editable:false,	
header:{
 left:'prev,next today',
 center:'title',

},
events: 'https://aneurinj.com/bookingtable',
selectable:true,
selectHelper:true,
select: function(start, end, allDay)
{
 var titlestart = $.fullCalendar.formatDate(start, "DD-MM-Y HH:mm:ss");


 var r = confirm("Request Time " + titlestart);
 if (r == true) {
      
           event.preventDefault();
           //Open dialog
        var email = 'aneurin@aneurinj.com';
        var subject = 'Booking '+titlestart;
        var emailBody = 'Create booking for '+titlestart   	
           showDialog(titlestart);
 } else {

 }
},
});
});
