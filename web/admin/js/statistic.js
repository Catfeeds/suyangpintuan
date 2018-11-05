var tjtag = document.createElement('script');
tjtag.type = 'text/javascript';
tjtag.src = '/statistics/doLog?referer='+encodeURIComponent(document.referrer)+'&url='+encodeURIComponent(window.location.href);
document.body.appendChild(tjtag);