/* ==========================================================================
   Template impression otomatis dari hasil audiometri.
   Dipakai oleh review_dokter.php (data dari server) dan form.php mode edit
   (data dari input, ikut berubah saat angka diedit).

   Aturan dikalibrasi terhadap 1.360 impression dokter yang sudah ada:
   - abnormal bila rata-rata AC >= 25 dB
   - derajat: ringan <= 45, sedang <= 60, sedang berat <= 75, berat <= 90, sangat berat
   - jenis: selisih AC-BC >= 15 dB -> konduktif (campur bila BC > 30 dB), selain itu saraf
   Hasil bukan diagnosis; dokter tetap memeriksa dan boleh mengubah teks.

   Pemakaian:
     var api = ImpressionTemplates.init({
         container: element,
         getData: function () { return { right_ac_250: 30, ... }; },
         onInsert: function (text) { ... }
     });
     api.refresh();  // bangun ulang template
   ========================================================================== */
(function (global) {
	'use strict';

	var FREQS = ['250', '500', '1000', '2000', '3000', '4000', '6000'];
	var SIDE = { right: 'kanan', left: 'kiri' };

	function values(data, ear, type) {
		return FREQS.map(function (f) {
			var raw = data[ear + '_' + type + '_' + f];
			var v = (raw === null || raw === undefined || raw === '') ? NaN : parseFloat(raw);
			return isNaN(v) ? null : v;
		});
	}

	function average(list) {
		var v = list.filter(function (x) { return x !== null; });
		return v.length ? v.reduce(function (a, b) { return a + b; }, 0) / v.length : null;
	}

	function degree(db) {
		if (db < 25) return null;
		if (db <= 45) return 'ringan';
		if (db <= 60) return 'sedang';
		if (db <= 75) return 'sedang berat';
		if (db <= 90) return 'berat';
		return 'sangat berat';
	}

	function analyze(data, ear) {
		var ac = values(data, ear, 'ac');
		var acAvg = average(ac);
		if (acAvg === null) return null;
		var bcAvg = average(values(data, ear, 'bc'));
		var result = { ear: ear, acAvg: acAvg, bcAvg: bcAvg, abnormal: acAvg >= 25, type: '', degree: null, dips: [] };

		if (result.abnormal) {
			result.degree = degree(acAvg);
			if (bcAvg === null) {
				result.type = '';
			} else if (acAvg - bcAvg >= 15) {
				result.type = bcAvg > 30 ? 'campur' : 'konduktif';
			} else {
				result.type = 'saraf';
			}
		} else {
			// Rata-rata normal tetapi ada frekuensi tertentu yang turun (> 30 dB)
			ac.forEach(function (v, i) {
				if (v !== null && v > 30) result.dips.push({ freq: FREQS[i], db: v });
			});
		}
		return result;
	}

	function phrase(r) {
		return 'Tuli ' + (r.type ? r.type + ' ' : '') + 'derajat ' + r.degree;
	}

	function joinList(items) {
		if (items.length <= 1) return items.join('');
		return items.slice(0, -1).join(', ') + ' dan ' + items[items.length - 1];
	}

	// Telinga normal tidak disebut pada kalimat gangguan, sama seperti tulisan dokter
	function abnormalSentence(list) {
		if (list.length === 2 && phrase(list[0]) === phrase(list[1])) {
			return phrase(list[0]) + ' bilateral.';
		}
		return list.map(function (r) { return phrase(r) + ' telinga ' + SIDE[r.ear] + '.'; }).join(' ');
	}

	function dipSentence(results) {
		var withDips = results.filter(function (r) { return r.dips.length; });
		if (!withDips.length) return null;
		var maxDb = Math.max.apply(null, withDips.map(function (r) {
			return Math.max.apply(null, r.dips.map(function (d) { return d.db; }));
		}));
		var level = degree(Math.max(maxDb, 25)) || 'ringan';
		var dipText = function (r) { return joinList(r.dips.map(function (d) { return d.freq; })) + ' Hz'; };
		var where;
		if (withDips.length === 2 && dipText(withDips[0]) === dipText(withDips[1])) {
			where = 'pada frekuensi ' + dipText(withDips[0]) + ' bilateral';
		} else {
			where = 'pada ' + withDips.map(function (r) {
				return 'frekuensi ' + dipText(r) + ' telinga ' + SIDE[r.ear];
			}).join(', ');
		}
		return 'Ambang dengar kedua telinga dalam batas normal. Terdapat penurunan derajat ' + level + ' ' + where + '.';
	}

	// Mengembalikan { results, normal: [{label,text}], abnormal: [{label,text}] }
	function build(data) {
		var results = ['right', 'left'].map(function (ear) { return analyze(data, ear); }).filter(Boolean);
		var out = { results: results, normal: [], abnormal: [] };
		if (!results.length) return out;

		var abnormals = results.filter(function (r) { return r.abnormal; });
		if (!abnormals.length) {
			out.normal.push({ label: 'Normal bilateral', text: 'Ambang dengar kedua telinga dalam batas normal.' });
			var dip = dipSentence(results);
			if (dip) out.normal.push({ label: 'Normal dengan penurunan frekuensi tertentu', text: dip });
		} else {
			out.abnormal.push({
				label: abnormals.length === 2 ? 'Kedua telinga' : 'Telinga ' + SIDE[abnormals[0].ear],
				text: abnormalSentence(abnormals)
			});
			if (abnormals.length === 2) {
				abnormals.forEach(function (r) {
					out.abnormal.push({ label: 'Telinga ' + SIDE[r.ear] + ' saja', text: abnormalSentence([r]) });
				});
			}
		}
		return out;
	}

	function addGroup(container, title, items, btnClass, onInsert) {
		if (!items.length) return;
		var group = document.createElement('div');
		group.className = 'mb-2';
		var heading = document.createElement('div');
		heading.className = 'fw-semibold small mb-1';
		heading.textContent = title;
		group.appendChild(heading);
		var wrap = document.createElement('div');
		wrap.className = 'd-flex gap-1 flex-wrap';
		items.forEach(function (item) {
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'btn btn-sm ' + btnClass;
			btn.textContent = item.label;
			btn.title = item.text;
			btn.addEventListener('click', function () { onInsert(item.text); });
			wrap.appendChild(btn);
		});
		group.appendChild(wrap);
		container.appendChild(group);
	}

	function render(container, data, onInsert) {
		while (container.firstChild) container.removeChild(container.firstChild);
		var built = build(data);
		if (!built.results.length) {
			var empty = document.createElement('span');
			empty.className = 'text-body-secondary small';
			empty.textContent = 'Data audiometri belum lengkap untuk membuat template.';
			container.appendChild(empty);
			return;
		}
		var summary = document.createElement('div');
		summary.className = 'small text-body-secondary mb-2';
		summary.textContent = 'Rata-rata AC: ' + built.results.map(function (r) {
			return 'telinga ' + SIDE[r.ear] + ' ' + r.acAvg.toFixed(1) + ' dB';
		}).join(', ');
		container.appendChild(summary);
		addGroup(container, 'Normal', built.normal, 'btn-outline-success', onInsert);
		addGroup(container, 'Gangguan pendengaran', built.abnormal, 'btn-outline-warning', onInsert);
	}

	function init(options) {
		var api = {
			refresh: function () {
				if (!options.container) return;
				render(options.container, options.getData(), options.onInsert);
			}
		};
		api.refresh();
		return api;
	}

	global.ImpressionTemplates = { init: init, build: build };
})(window);
