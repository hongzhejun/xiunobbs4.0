var cf = {}; // namespace

cf.is_complex_password = function(password) {
	if(password.length < 8) {
		return 0;
	}
	var ls = 0;
	if (password.match(/([a-z])+/)) {
		ls++;
	}
	if (password.match(/([0-9])+/)) {
		ls++;
	}
	if (password.match(/([A-Z])+/)) {
		ls++;
	}
	if (password.match(/[^a-zA-Z0-9]+/)) {
		ls++;
	}
	return ls;
};

console.log('cf.js loaded');