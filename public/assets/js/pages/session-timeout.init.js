/*
Template Name: Clivax - Admin & Dashboard Template
Author: Codebucks

File: Session Timeout Js File
*/

$.sessionTimeout({
	keepAliveUrl: 'pages-starter.html',
	logoutButton:'Logout',
	logoutUrl: 'auth-login.html',
	redirUrl: 'auth-lock-screen.html',
	warnAfter: 3000,
	redirAfter: 30000,
	countdownMessage: 'Redirecting in {timer} seconds.'
});