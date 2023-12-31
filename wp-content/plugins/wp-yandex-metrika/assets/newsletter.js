jQuery(function($) {
	if (window.wpym === undefined){return;}

	const {counters} = window.wpym;

	const params = new URLSearchParams(window.location.search)

	if (!params.has('nm')) return;
	if (params.get('nm') !== 'confirmed') return;

	counters.forEach(counter => {
		ym(counter.number, 'reachGoal', 'ym-subscribe');
	});
});
