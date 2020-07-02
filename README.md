This is a small project that kept me busy during the Covid-19 lockdown.

# Raspberry Pi Local Portal

A web-based one page application that display stats about network drives and devices, internet connection and other information. It is running on a [Raspberry Pi](https://www.raspberrypi.org) and uses the [Apache Web Server](https://httpd.apache.org), [MariaDB](https://mariadb.org), [Material Design Lite](https://getmdl.io/index.html) and [d3](https://d3js.org).

![](https://raw.githubusercontent.com/musevarg/Raspberry-Pi-Local-Portal/master/scr1.png?token=ALYNMCCXBYITPFMCVGN62YS67X4LE)

The first block provides IP information. I find it useful in checking whether I am successfully connected to a VPN server or not.

The second block simply returns weather information in my location.

The third block returns storage information of the two external drives and the SD Card connected to the Raspberry Pi.

The bottom part of the fourth block contains an html `marquee` that displays the latest news from [France 24](https://www.france24.com/en/) and [Loudersound](https://www.loudersound.com). If clicked, a new tab opens the article on the respective website. The top part of the block will play France 24 Live if clicked on.

The fifth block displays the status and information of the devices connected to the network. Every time a new device is seen, it is added to a local db. The process to resolve hostnames takes a few seconds per devices so the data is not refreshed on every page display, instead I added a refresh button to do this.

The last block, located at the very bottom, simply displays whether I am connected to the internet or not. I added this because I lose my internet connection once in a while. If the connection cannot be established, a red WiFi incon is displayed with a warning message. If the internet connection is live, the WiFi icon is green and fortune message is returned from [FortuneCookieMessage.com](http://www.fortunecookiemessage.com).

Finally, the Raspberry Pi runs a [Plex Media Server](https://www.plex.tv) and [Pi-Hole](https://pi-hole.net). Both these services come with their own local web-based control panels. Since these web apps are stored on the Pi as well, I added a link to them in the menu on the left of the portal. The top part shows the Pi's CPU temperature and RAM usage.
