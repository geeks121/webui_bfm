"use strict";

const modalShowcase = `
<!-- Demo Showcase -->
<style>
.card-documentation {
	display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
	padding: 16px 25px;
    background: #161B2C;
    color: #fff;
    border-radius: 10px;
    border: 1px solid #ffffff14;
}
</style>
<div class="modal fade" tabindex="-1" id="modalShowcase">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen p-5">
		<div class="modal-content rounded-4">
			<div class="modal-header px-md-5 py-md-4 mt-2 mb-3 shadow-sm border-0">
				<h3 class="h5 fw-extrabold mb-0">Choose Layouts Dashboard</h3>
				<button type="button" class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
				<path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
			  </svg>
			  </button>
			</div>
			<div class="modal-body px-md-5">
				<div class="row g-5 pb-5"> 
					<div class="col-md-6 col-lg-4">
						<div class="card-documentation h-100">
							<div class="d-flex align-items-center flex-column justify-content-center text-center">
								<img src="assets/img/kaiadmin/logo_documentation.png" height="60" alt="Read Documentation">
								<div class="docs-info ms-3 mb-4">
									<h6 class="fw-bold mb-0 op-8 mt-1">Need help?</h6>
									<p class="fw-bold mb-0 op-5">Please check our docs</p>
								</div>
							</div>
							<a href="../../documentation/index.html" class="btn btn-primary w-100 mb-3">Documentation</a>
							<a href="https://kaiadmin.themekita.com/" class="btn btn-secondary w-100">Buy Now</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo1/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									Classic Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo1.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo2/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									White Classic Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo2.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo3/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									Dark Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo3.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo4/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									Creative Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo4.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo5/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									Trendy Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo5.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo6/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									Trendy 2 Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo6.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo7/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									Horizontal Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo7.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo8/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									Enterprise Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo8.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="preview-showcase shadow-sm">
							<a href="../demo9/index.html" data-kt-href="true" class="preview-thumbnail">
								<h3 class="preview-title">
									Futuristic Dashboard	
								</h3>

								<div class="overflow-hidden">
									<img src="assets/img/kaiadmin/demo9.png" class="w-100 rounded-1 shadow-sm preview-img" data-loaded="true">
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Demo Showcase -->
`;

// window.addEventListener('load', function(event) {
//   $("body").append(modalShowcase);

//   const myModal = new bootstrap.Modal("#modalShowcase");
//   myModal.show();
// });


// Cicle Chart
Circles.create({
	id:           'task-complete',
	radius:       50,
	value:        80,
	maxValue:     100,
	width:        5,
	text:         function(value){return value + '%';},
	colors:       ['#36a3f7', '#fff'],
	duration:     400,
	wrpClass:     'circles-wrp',
	textClass:    'circles-text',
	styleWrapper: true,
	styleText:    true
})

//Notify
$.notify({
	icon: 'icon-bell',
	title: 'Kaiadmin',
	message: 'Premium Bootstrap 5 Admin Dashboard',
},{
	type: 'secondary',
	placement: {
		from: "bottom",
		align: "right"
	},
	time: 1000,
});

// Jsvectormap
var world_map = new jsVectorMap({
	selector: "#world-map",
	map: "world",
	zoomOnScroll: false,
	regionStyle: {
		hover: {
			fill: '#435ebe'
		}
	},
	markers: [
		{
			name: 'Indonesia',
			coords: [-6.229728, 106.6894311],
			style: {
				fill: '#435ebe'
			}
		},
		{
			name: 'United States',
			coords: [38.8936708, -77.1546604],
			style: {
				fill: '#28ab55'
			}
		},
		{
			name: 'Russia',
			coords: [55.5807481, 36.825129],
			style: {
				fill: '#f3616d'
			}
		},
		{
			name: 'China',
			coords: [39.9385466, 116.1172735]
		},
		{
			name: 'United Kingdom',
			coords: [51.5285582, -0.2416812]
		},
		{
			name: 'India',
			coords: [26.8851417, 75.6504721]
		},
		{
			name: 'Australia',
			coords: [-35.2813046, 149.124822]
		},
		{
			name: 'Brazil',
			coords: [-22.9140693, -43.5860681]
		},
		{
			name: 'Egypt',
			coords: [26.834955, 26.3823725]
		},
	],
	onRegionTooltipShow(event, tooltip) {
		tooltip.css({ backgroundColor: '#435ebe' })
	}
});

//Chart

var ctx = document.getElementById('statisticsChart').getContext('2d');

var statisticsChart = new Chart(ctx, {
	type: 'line',
	data: {
		labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
		datasets: [ {
			label: "Subscribers",
			borderColor: '#f3545d',
			pointBackgroundColor: 'rgba(243, 84, 93, 0.6)',
			pointRadius: 0,
			backgroundColor: 'rgba(243, 84, 93, 0.4)',
			legendColor: '#f3545d',
			fill: true,
			borderWidth: 2,
			data: [154, 184, 175, 203, 210, 231, 240, 278, 252, 312, 320, 374]
		}, {
			label: "New Visitors",
			borderColor: '#fdaf4b',
			pointBackgroundColor: 'rgba(253, 175, 75, 0.6)',
			pointRadius: 0,
			backgroundColor: 'rgba(253, 175, 75, 0.4)',
			legendColor: '#fdaf4b',
			fill: true,
			borderWidth: 2,
			data: [256, 230, 245, 287, 240, 250, 230, 295, 331, 431, 456, 521]
		}, {
			label: "Active Users",
			borderColor: '#177dff',
			pointBackgroundColor: 'rgba(23, 125, 255, 0.6)',
			pointRadius: 0,
			backgroundColor: 'rgba(23, 125, 255, 0.4)',
			legendColor: '#177dff',
			fill: true,
			borderWidth: 2,
			data: [542, 480, 430, 550, 530, 453, 380, 434, 568, 610, 700, 900]
		}]
	},
	options : {
		responsive: true, 
		maintainAspectRatio: false,
		legend: {
			display: false
		},
		tooltips: {
			bodySpacing: 4,
			mode:"nearest",
			intersect: 0,
			position:"nearest",
			xPadding:10,
			yPadding:10,
			caretPadding:10
		},
		layout:{
			padding:{left:5,right:5,top:15,bottom:15}
		},
		scales: {
			yAxes: [{
				ticks: {
					fontStyle: "500",
					beginAtZero: false,
					maxTicksLimit: 5,
					padding: 10
				},
				gridLines: {
					drawTicks: false,
					display: false
				}
			}],
			xAxes: [{
				gridLines: {
					zeroLineColor: "transparent"
				},
				ticks: {
					padding: 10,
					fontStyle: "500"
				}
			}]
		}, 
		legendCallback: function(chart) { 
			var text = []; 
			text.push('<ul class="' + chart.id + '-legend html-legend">'); 
			for (var i = 0; i < chart.data.datasets.length; i++) { 
				text.push('<li><span style="background-color:' + chart.data.datasets[i].legendColor + '"></span>'); 
				if (chart.data.datasets[i].label) { 
					text.push(chart.data.datasets[i].label); 
				} 
				text.push('</li>'); 
			} 
			text.push('</ul>'); 
			return text.join(''); 
		}  
	}
});

var myLegendContainer = document.getElementById("myChartLegend");

// generate HTML legend
myLegendContainer.innerHTML = statisticsChart.generateLegend();

// bind onClick event to all LI-tags of the legend
var legendItems = myLegendContainer.getElementsByTagName('li');
for (var i = 0; i < legendItems.length; i += 1) {
	legendItems[i].addEventListener("click", legendClickCallback, false);
}

var dailySalesChart = document.getElementById('dailySalesChart').getContext('2d');

var myDailySalesChart = new Chart(dailySalesChart, {
	type: 'line',
	data: {
		labels:["January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September"],
		datasets:[ {
			label: "Sales Analytics", fill: !0, backgroundColor: "rgba(255,255,255,0.2)", borderColor: "#fff", borderCapStyle: "butt", borderDash: [], borderDashOffset: 0, pointBorderColor: "#fff", pointBackgroundColor: "#fff", pointBorderWidth: 1, pointHoverRadius: 5, pointHoverBackgroundColor: "#fff", pointHoverBorderColor: "#fff", pointHoverBorderWidth: 1, pointRadius: 1, pointHitRadius: 5, data: [65, 59, 80, 81, 56, 55, 40, 35, 30]
		}]
	},
	options : {
		maintainAspectRatio:!1, legend: {
			display: !1
		}
		, animation: {
			easing: "easeInOutBack"
		}
		, scales: {
			yAxes:[ {
				display:!1, ticks: {
					fontColor: "rgba(0,0,0,0.5)", fontStyle: "bold", beginAtZero: !0, maxTicksLimit: 10, padding: 0
				}
				, gridLines: {
					drawTicks: !1, display: !1
				}
			}
			], xAxes:[ {
				display:!1, gridLines: {
					zeroLineColor: "transparent"
				}
				, ticks: {
					padding: -20, fontColor: "rgba(255,255,255,0.2)", fontStyle: "bold"
				}
			}
			]
		}
	}
});

$("#activeUsersChart").sparkline([112,109,120,107,110,85,87,90,102,109,120,99,110,85,87,94], {
	type: 'bar',
	height: '100',
	barWidth: 9,
	barSpacing: 10,
	barColor: 'rgba(255,255,255,.3)'
});
