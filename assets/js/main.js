$('.date-picker').datepicker({
	format: "yyyy-mm-dd",
	language: "es"
});

var formReport = function() {
	var self = this;

	this.user = $('#report-user-input');
	this.date = $('#report-day-input');
	this.fromTo = $('#report-range-input');
	this.option = $('#report-type');

	this.init = function() {
		var option = self.option.val();

		if(option == 'user') {
			self.user.show();
			self.date.show();
			self.fromTo.hide();
		}
		if(option == 'date') {
			self.user.hide();
			self.date.show();
			self.fromTo.hide();
		}
		if(option == 'range') {
			self.user.hide();
			self.date.hide();
			self.fromTo.show();
		}
		if(option == 'rangeById') {
			self.user.show();
			self.date.hide();
			self.fromTo.show();
		}
	}

	this.option.change(this.init);

	this.init();

};

new formReport;

var Counter = function(id, options, starttime) {
	var self = this;
	this.clock = document.getElementById(id);
	this.options = (typeof options == 'object') ? options: {days: true, hours: true, minutes: true, seconds: true};
	this.starttime = starttime || {year: 1970, month: 1, day: 1, hour: 0, minutes: 0, seconds: 0};

	this.getTimeRemaining = function(starttime) {
		var starttime = this.starttime || starttime;

		if (typeof(starttime) == 'object') {
			var t = new Date().getTime() - new Date(starttime.year, starttime.month, starttime.day, starttime.hour, starttime.minutes, starttime.seconds).getTime();

			var seconds = Math.floor( (t/1000) % 60 ),
				minutes = Math.floor( (t/1000/60) % 60 ),
				hours = Math.floor( (t/(1000*60*60)) % 24 ),
				days = Math.floor( t/(1000*60*60*24) );

			return {
				'total': t,
				'days': days,
				'hours': hours,
				'minutes': minutes,
				'seconds': seconds
			};
		}
		return false;
	}

	this.updateTime = function() {
		var t = self.getTimeRemaining(self.starttime),
			container = self.clock,
			options = self.options;

		var d = (t.days > 0) ? t.days : '',
			h = (t.hours.toString().length > 1) ? t.hours: '0'+t.hours,
			m = (t.minutes.toString().length > 1) ? t.minutes: '0'+t.minutes,
			s = (t.seconds.toString().length > 1) ? t.seconds: '0'+t.seconds;

		output = '<div class="counter-container">';

		if (options.days)
			output += '<div class="counter-days">' + d + '</div>';
		if (options.hours)
			output += '<div class="counter-hours">' + h + '</div>';
		if (options.minutes)
			output += '<div class="counter-minutes">' + m + '</div>';
		if (options.seconds)
			output += '<div class="counter-seconds">' + s + '</div>';

		output += '</div>';

		container.innerHTML = output;

		if (t.total <= 0){
		  clearInterval(self.timeinterval);
		}
	}
	
	if (this.clock != null)
		this.timeinterval = setInterval( this.updateTime, 1000 );
}








