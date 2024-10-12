const CoolKidsJs = {
	init: function () {
		this.createSparkles();
		this.initLoginForm();
		this.initSignupForm();
	},

	createSparkles: function () {
		const sparklesContainer = document.querySelector('.sparkles');
		if (!sparklesContainer) return;

		for (let i = 0; i < 50; i++) {
			const sparkle = document.createElement('div');
			sparkle.classList.add('sparkle');
			sparkle.style.left = `${Math.random() * 100}%`;
			sparkle.style.top = `${Math.random() * 100}%`;
			sparkle.style.animationDelay = `${Math.random() * 5}s`;
			sparklesContainer.appendChild(sparkle);
		}
	},

	initLoginForm: function () {
		const loginForm = document.getElementById('cool-kids-login-form');
		if (!loginForm) return;

		loginForm.addEventListener('submit', this.handleLogin.bind(this));
	},

	initSignupForm: function () {
		const signupForm = document.getElementById('cool-kids-registration-form');
		if (!signupForm) return;

		signupForm.addEventListener('submit', this.handleSignup.bind(this));
	},

	handleLogin: function (event) {
		event.preventDefault(); // This line is crucial

		const email = document.getElementById('login-email').value;
		const errorElement = document.getElementById('login-error');
		const successElement = document.getElementById('login-success');
		const nonce = document.getElementById('cool-kids-login-nonce').value;

		if (!this.validateEmail(email)) {
			this.showError(errorElement, 'Please enter a valid email address.');
			return;
		}

		this.ajaxRequest('cool_kids_login', { email: email, nonce: nonce }, function (response) {
			if (response.success) {
				this.showSuccess(successElement, 'Logged in successfully!');
				setTimeout(() => {
					window.location.href = '/dashboard';
				}, 1500);
			} else {
				this.showError(errorElement, response.data || 'Login failed. Please try again.');
			}
		}.bind(this));
	},

	handleSignup: function (event) {
		event.preventDefault(); // This line is crucial

		const email = document.getElementById('signup-email').value;
		const errorElement = document.getElementById('registration-error');
		const successElement = document.getElementById('registration-success');
		const nonce = document.getElementById('cool-kids-registration-nonce').value;

		if (!this.validateEmail(email)) {
			this.showError(errorElement, 'Please enter a valid email address.');
			return;
		}

		this.ajaxRequest('cool_kids_register', { email: email, nonce: nonce }, function (response) {
			if (response.success) {
				this.showSuccess(successElement, 'Registered successfully!');
				setTimeout(() => {
					window.location.href = '/login';
				}, 1500);
			} else {
				this.showError(errorElement, response.data || 'Registration failed. Please try again.');
			}
		}.bind(this));
	},

	ajaxRequest: function (action, data, callback) {
		const xhr = new XMLHttpRequest();
		xhr.open('POST', coolkidsnetworkData.ajax_url, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function () {
			if (xhr.status === 200) {
				let response;
				try {
					response = JSON.parse(xhr.responseText);
				} catch (e) {
					console.error('Error parsing JSON response:', e);
					callback({ success: false, data: 'Invalid server response' });
					return;
				}
				callback(response);
			} else {
				callback({ success: false, data: 'Server error' });
			}
		};
		const encryptedData = this.encryptData(data);
		const formData = new URLSearchParams({
			action: action,
			data: encryptedData
		});
		xhr.send(formData.toString());
	},

	validateEmail: function (email) {
		const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(String(email).toLowerCase());
	},

	showError: function (element, message) {
		this.removeAllNotifications();
		if (element) {
			element.textContent = message;
			element.style.display = 'block';
			element.style.color = '#721c24';
			element.style.backgroundColor = '#f8d7da';
			element.style.border = '1px solid #f5c6cb';
			element.style.borderRadius = '4px';
			element.style.padding = '10px';
			element.style.marginBottom = '10px';
		} else {
			console.error(message);
		}
	},

	showSuccess: function (element, message) {
		this.removeAllNotifications();
		if (element) {
			element.textContent = message;
			element.style.display = 'block';
			element.style.color = '#155724';
			element.style.backgroundColor = '#d4edda';
			element.style.border = '1px solid #c3e6cb';
			element.style.borderRadius = '4px';
			element.style.padding = '10px';
			element.style.marginBottom = '10px';
		} else {
			console.log(message);
		}
	},

	removeAllNotifications: function () {
		const notifications = document.querySelectorAll('.error-message, .success-message');
		notifications.forEach(notification => {
			notification.style.display = 'none';
			notification.textContent = '';
		});
	},

	encryptData: function (data) {
		const key = 'coolkidsnetwork-crypt';
		let encrypted = '';
		const dataString = JSON.stringify(data);
		for (let i = 0; i < dataString.length; i++) {
			encrypted += String.fromCharCode(dataString.charCodeAt(i) ^ key.charCodeAt(i % key.length));
		}
		return btoa(encrypted);
	},

	decryptData: function (encryptedData) {
		const key = 'coolkidsnetwork-crypt';
		let decrypted = '';
		const data = atob(encryptedData);
		for (let i = 0; i < data.length; i++) {
			decrypted += String.fromCharCode(data.charCodeAt(i) ^ key.charCodeAt(i % key.length));
		}
		return JSON.parse(decrypted);
	}
};

document.addEventListener('DOMContentLoaded', function () {
	CoolKidsJs.init();
});