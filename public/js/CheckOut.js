// Justo for informative purposes. not send to backend
const Plans = {
    "plan_anual": {
        "plan": "Plan anual",
        "slug": "anual",
        "price": 1199,
        "price_string": "$1,199",
        "price_before_discount": "$1,199",
        "ads": 3,
        "dicount": 300,
        "discount_string": "-$300",
        "total": 899,
        "total_string": "$899",
        "currency": "MXN",
        "visit_counter": "Incluido",
    },
    "plan_semestral": {
        "plan": "Plan semestral",
        "slug": "semestral",
        "price": 599,
        "price_string": "$599",
        "price_before_discount": "$599",
        "ads": 2,
        "dicount": 100,
        "discount_string": "-$100",
        "total": 499,
        "total_string": "$499",
        "currency": "MXN",
        "visit_counter": "Incluido",
    },
    "plan_mensual": {
        "plan": "Plan mensual",
        "slug": "mensual",
        "price": 99,
        "price_string": "$99",
        "price_before_discount": "$0",
        "ads": 1,
        "dicount": 0,
        "discount_string": "-$0",
        "total": 99,
        "total_string": "$99",
        "currency": "MXN",
        "visit_counter": "Incluido",
    }
}

function normalizePlanNT(Plan) {
    // check if plan has _nt in the name
    if (Plan.includes("_nt")) {
        // remove _nt from the name
        Plan = Plan.replace("_nt", "");
    }
    return Plan;
}

function getFromDom(ID){
    return document.getElementById(ID) ?? document.createElement("div");
}

function getPlanSelectedInformation(Plan) {
    const plan = normalizePlanNT(Plan);
    return Plans[plan];
}

function setPlanSelectedInformation(selectedPlan, planFields = {}) {

    const planData = getPlanSelectedInformation(selectedPlan);

    // get from DOM or set default
    const planPrice = getFromDom(planFields.planPrice);
    const planSlug = getFromDom(planFields.planSlug);
    const planAds = getFromDom(planFields.planAds);
    const planDiscount = getFromDom(planFields.planDiscount);
    const planTotalPrice = getFromDom(planFields.planTotalPrice);
    const planBeforeDiscountPrice = getFromDom(planFields.planBeforeDiscountPrice);

    // set plan price
    planPrice.textContent = planData.price_string;
    planSlug.textContent = planData.slug;
    planAds.textContent = planData.ads;
    planDiscount.textContent = planData.discount_string;
    planBeforeDiscountPrice.textContent = planData.price_before_discount;
    planTotalPrice.textContent = planData.total_string;
}