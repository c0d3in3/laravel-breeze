<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex">
                        <div class="w-2/3">
                            <p>World Map</p>
                            <div class="w-full h-96" id="map"></div>
                        </div>
                        <div class="w-1/3">
                            <div class="rounded shadow p-2">
                                <h3 class="text-x1 text-indigo-500">
                                    Visited Countries
                                </h3>
                                <div class="flex">
                                    <Select2
                                        class="w-96"
                                        v-model="selectedVisitedCountry"
                                        :options="myOptions"
                                        :settings="{
                                            width: '100%',
                                            settingOption: value,
                                            ajax: {
                                                url: '/api/countries/list',
                                                dataType: 'json',
                                            },
                                        }"
                                        @select="visitedCountrySelected($event)"
                                    />
                                    <a
                                        href="javascript:void(0);"
                                        @click="addVisitedCountry"
                                        class="px-3 pt-1 text-md bg-green-400 hover:bg-green-300 font-semibold shadow hover:shadow-x1 rounded"
                                        >Add</a
                                    >
                                </div>

                                <div
                                    v-for="vc in visitedCountries"
                                    :key="vc.id"
                                >
                                    <div class="flex">
                                        <span>{{ vc.name }}</span>
                                        <a
                                            href="javascript:void(0);"
                                            @click="deleteVisitedCountry(vc)"
                                            class="px-3 pt-1 text-md bg-green-400 hover:bg-green-300 font-semibold shadow hover:shadow-x1 rounded"
                                            >X</a
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="rounded shadow p-2">
                                <h3 class="text-x1 text-indigo-500">
                                    Countries to Visit
                                </h3>
                                <div class="flex">
                                    <Select2
                                        class="w-96"
                                        v-model="selectedCountryToVisit"
                                        :options="myOptions"
                                        :settings="{
                                            width: '100%',
                                            settingOption: value,
                                            ajax: {
                                                url: '/api/countries/list',
                                                dataType: 'json',
                                            },
                                        }"
                                        @select="countryToVisitSelected($event)"
                                    />
                                    <a
                                        href="javascript:void(0);"
                                        @click="addToVisitCountry"
                                        class="px-3 pt-1 text-md bg-green-400 hover:bg-green-300 font-semibold shadow hover:shadow-x1 rounded"
                                        >Add</a
                                    >
                                </div>

                                <div
                                    v-for="vc in countriesToVisit"
                                    :key="vc.id"
                                >
                                    <div class="flex">
                                        <span>{{ vc.name }}</span>
                                        <a
                                            href="javascript:void(0);"
                                            @click="deleteCountryToVisit(vc)"
                                            class="px-3 pt-1 text-md bg-green-400 hover:bg-green-300 font-semibold shadow hover:shadow-x1 rounded"
                                            >X</a
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Select2 from "vue3-select2-component";

export default {
    components: {
        Select2,
        BreezeAuthenticatedLayout,
        Head,
    },
    data() {
        return {
            selectedVisitedCountry: "",
            selectedCountryToVisit: "",
            visitedCountries: [],
            countriesToVisit: [],
            map: {},
        };
    },
    methods: {
        visitedCountrySelected({ id, text }) {
            console.log({ id, text });
        },
        countryToVisitSelected({ id, text }) {
            console.log({ id, text });
        },
        getVisitedCountries() {
            axios.get("/api/countries/visited/").then((response) => {
                this.visitedCountries = response.data;
                response.data.forEach((element) => {
                    this.map.updateChoropleth({
                        [element.code]: "#ff0000",
                    });
                });
            });
        },
        getCountriesToVisit() {
            axios.get("/api/countries/tovisit/").then((response) => {
                this.countriesToVisit = response.data;
                response.data.forEach((element) => {
                    this.map.updateChoropleth({
                        [element.code]: "#ffff00",
                    });
                });
            });
        },
        addVisitedCountry() {
            if (this.selectedVisitedCountry !== "") {
                axios
                    .post("/api/countries/add-visited-country", {
                        countryID: this.selectedVisitedCountry,
                    })
                    .then((response) => {
                        this.getVisitedCountries();
                    });
            } else {
                console.warn("1you must choose country first");
            }
        },
        addToVisitCountry() {
            if (this.selectedCountryToVisit !== "") {
                axios
                    .post("/api/countries/add-country-to-visit", {
                        countryID: this.selectedCountryToVisit,
                    })
                    .then((response) => {
                        this.getCountriesToVisit();
                    });
            } else {
                console.warn(" 2syou must choose country first");
            }
        },
        deleteVisitedCountry(country) {
            axios
                .delete("/api/countries/delete-visited-country", {
                    params: {
                        countryID: country.id,
                    },
                })
                .then((response) => {
                    this.map.updateChoropleth({
                        [country.code]: "#ABDDA4",
                    });
                    this.getVisitedCountries();
                });
        },
        deleteCountryToVisit(country) {
            axios
                .delete("/api/countries/delete-country-to-visit", {
                    params: {
                        countryID: country.id,
                    },
                })
                .then((response) => {
                    this.map.updateChoropleth({
                        [country.code]: "#ABDDA4",
                    });
                    this.getCountriesToVisit();
                });
        },
        initMap() {
            this.map = new Datamap({
                element: document.getElementById("map"),
                projection: "mercator",
                fills: {
                    defaultFill: "#ABDDA4",
                    visitedCountry: "#fa0fa0",
                    countryToVisit: "#fa0fa0",
                },
            });
        },
    },
    created() {
        this.getVisitedCountries();
        this.getCountriesToVisit();
        setTimeout(() => {
            this.initMap();
        }, 500);
    },
};
</script>
