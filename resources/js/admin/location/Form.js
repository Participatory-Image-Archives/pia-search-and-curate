import AppForm from '../app-components/Form/AppForm';

Vue.component('location-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                label:  '' ,
                geonames_id:  '' ,
                geonames_url:  '' ,
                latitude:  '' ,
                longitude:  '' ,
                place_id:  '' ,
                
            }
        }
    }

});