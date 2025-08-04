document.addEventListener('DOMContentLoaded', () => {
  const formEl     = document.querySelector('.reservation-form');
  const btn        = document.getElementById('btn-review');
  const groups     = document.querySelectorAll('[data-type]');
  const selDest    = document.getElementById('destination');
  const depDateEl  = document.getElementById('date_depart');
  const retDateEl  = document.getElementById('date_retour');
  const cpEl       = document.getElementById('cp');
  const gsmEl      = document.getElementById('gsm');
  const sumFlight  = document.getElementById('sum-flight');
  const sumTrav    = document.getElementById('sum-travellers');
  const sumMeals   = document.getElementById('sum-meals');
  const sumDrinks  = document.getElementById('sum-drinks');
  const sumBag     = document.getElementById('sum-baggage');
  const sumPay     = document.getElementById('sum-payment');
  const sumHT      = document.getElementById('sum-ht');
  const sumTVA     = document.getElementById('sum-tva');
  const totalEst   = document.getElementById('total-estimate');

  const todayStr = new Date().toISOString().split('T')[0];
  depDateEl.min = todayStr;

  const initRetMin = () => {
    const dep = new Date(depDateEl.value || todayStr);
    const nextDay = new Date(dep.getTime() + 24 * 60 * 60 * 1000);
    retDateEl.min = nextDay.toISOString().split('T')[0];
    if (retDateEl.value <= depDateEl.value) {
      retDateEl.value = retDateEl.min;
    }
  };
  initRetMin();

  function updateButton() {
    const totalTrav = Array.from(groups).reduce(
      (acc, g) => acc + parseInt(g.querySelector('input[type=number]').value || 0, 10),
      0
    );
    btn.disabled = totalTrav === 0 || retDateEl.value <= depDateEl.value || !formEl.checkValidity();
  }

  function updateVisibility() {
    groups.forEach(g => {
      const nb = parseInt(g.querySelector('input[type=number]').value || 0, 10);
      g.querySelector('.baggage-field')?.classList.toggle('hidden', nb === 0);
    });
  }

  function updateSummary() {
    const destOption = selDest.selectedOptions[0];
    const flightBase = destOption ? parseFloat(destOption.dataset.price) : 0;
    const qtyAdult = parseInt(document.getElementById('nb_adultes').value || 0, 10);
    const qtyChild = parseInt(document.getElementById('nb_enfants').value || 0, 10);
    const qtyBaby  = parseInt(document.getElementById('nb_bebes').value   || 0, 10);
    const totalPax = qtyAdult + qtyChild + qtyBaby;

    const flightCost = flightBase * totalPax;

    const today = new Date();
    const depDate = new Date(depDateEl.value);
    const monthsDiff = (depDate.getFullYear() - today.getFullYear()) * 12 + (depDate.getMonth() - today.getMonth());
    const earlyDiscount = monthsDiff >= 2 ? +(flightCost * 0.05).toFixed(2) : 0;
    let costAfterDisc = +(flightCost - earlyDiscount).toFixed(2);

    let mealsCost = 0;
    const nbRepasEligibles = qtyAdult + qtyChild;
    ['entree_id', 'plat_id', 'dessert_id'].forEach(id => {
      const sel = document.getElementById(id);
      if (sel && sel.value) {
        const prixUnitaire = parseFloat(sel.selectedOptions[0].dataset.price);
        mealsCost += prixUnitaire * nbRepasEligibles;
      }
    });
    mealsCost = +mealsCost.toFixed(2);

    let drinksCost = 0;
    document.querySelectorAll('input[name^="boisson_"]').forEach(inp => {
      const pr = parseFloat(inp.dataset.price);
      const qt = parseInt(inp.value || 0, 10);
      drinksCost += pr * qt;
    });
    drinksCost = +drinksCost.toFixed(2);

    const totalKg = parseFloat(document.getElementById('poids_bagages').value || 0);
    const maxKg = 20 * totalPax;
    const over = Math.max(0, totalKg - maxKg);
    const bagFee = +(over * 25).toFixed(2);

    const bagAdult  = document.querySelector('[name="bag_cabine_adult"]')?.checked ? 1 : 0;
    const bagChild  = document.querySelector('[name="bag_cabine_enfant"]')?.checked ? 1 : 0;
    const bagBaby   = document.querySelector('[name="bag_cabine_bebe"]')?.checked ? 1 : 0;

    const validBagAdult = Math.min(bagAdult, qtyAdult);
    const validBagChild = Math.min(bagChild, qtyChild);
    const validBagBaby  = Math.min(bagBaby, qtyBaby);

    const totalCabin = validBagAdult + validBagChild + validBagBaby;
    const bagCabinFee = +(totalCabin * 10).toFixed(2);

    const payMode = document.querySelector('input[name=paiement_mode]:checked')?.value;
    const payFee = (payMode === '1') ? 30.00 : (payMode === '2') ? 25.00 : 20.00;

    let subHT = +(costAfterDisc + mealsCost + drinksCost + bagFee + bagCabinFee + payFee).toFixed(2);

    const retDate = new Date(retDateEl.value);
    const daysStay = (retDate - depDate) / (1000 * 3600 * 24);
    if (daysStay < 30) {
      subHT *= 0.98;
      subHT = +subHT.toFixed(2);
    }

    const tva = +(subHT * 0.21).toFixed(2);
    const total = +(subHT + tva).toFixed(2);

    sumFlight.textContent = flightCost.toFixed(2);
    sumTrav.textContent   = totalPax;
    sumMeals.textContent  = mealsCost.toFixed(2);
    sumDrinks.textContent = drinksCost.toFixed(2);
    sumBag.textContent    = (bagFee + bagCabinFee).toFixed(2);
    sumPay.textContent    = payFee.toFixed(2);
    sumHT.textContent     = subHT.toFixed(2);
    sumTVA.textContent    = tva.toFixed(2);
    totalEst.textContent  = total.toFixed(2);
  }

  formEl.addEventListener('submit', e => {
    let valid = true;

    if (retDateEl.value <= depDateEl.value) {
      alert('La date de retour doit être strictement supérieure à la date de départ.');
      valid = false;
    }

    const email = formEl.querySelector('[name="mail"]').value;
    if (!/^[^@\s]+@[^@\s]+\.[^@\s]{3,}$/.test(email)) {
      alert("L'e-mail doit contenir un @ et au moins 3 caractères après le point.");
      valid = false;
    }

    ['tel', 'gsm'].forEach(name => {
      const val = formEl.querySelector(`[name="${name}"]`)?.value || '';
      const regex = name === 'tel' ? /^\d{6,15}$/ : /^\d{1,10}$/;
      if (!regex.test(val)) {
        alert(`${name.toUpperCase()} invalide : uniquement chiffres (${regex}).`);
        valid = false;
      }
    });

    const cp = cpEl?.value || '';
    if (!/^\d{1,10}$/.test(cp)) {
      alert("Code postal invalide : 1 à 10 chiffres uniquement.");
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
    }
  });

  ['input', 'change'].forEach(evt =>
    formEl.addEventListener(evt, () => {
      initRetMin();
      updateVisibility();
      updateSummary();
      updateButton();
    })
  );

  depDateEl.addEventListener('change', initRetMin);
  birthEl.max = new Date().toISOString().split('T')[0];
  updateVisibility();
  updateSummary();
  updateButton();
});
