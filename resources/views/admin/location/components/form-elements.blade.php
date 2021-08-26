<div class="form-group row align-items-center" :class="{'has-danger': errors.has('label'), 'has-success': fields.label && fields.label.valid }">
    <label for="label" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.location.columns.label') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.label" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('label'), 'form-control-success': fields.label && fields.label.valid}" id="label" name="label" placeholder="{{ trans('admin.location.columns.label') }}">
        <div v-if="errors.has('label')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('label') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('geonames_id'), 'has-success': fields.geonames_id && fields.geonames_id.valid }">
    <label for="geonames_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.location.columns.geonames_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.geonames_id" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('geonames_id'), 'form-control-success': fields.geonames_id && fields.geonames_id.valid}" id="geonames_id" name="geonames_id" placeholder="{{ trans('admin.location.columns.geonames_id') }}">
        <div v-if="errors.has('geonames_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('geonames_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('geonames_url'), 'has-success': fields.geonames_url && fields.geonames_url.valid }">
    <label for="geonames_url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.location.columns.geonames_url') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.geonames_url" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('geonames_url'), 'form-control-success': fields.geonames_url && fields.geonames_url.valid}" id="geonames_url" name="geonames_url" placeholder="{{ trans('admin.location.columns.geonames_url') }}">
        <div v-if="errors.has('geonames_url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('geonames_url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('latitude'), 'has-success': fields.latitude && fields.latitude.valid }">
    <label for="latitude" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.location.columns.latitude') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.latitude" v-validate="'decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('latitude'), 'form-control-success': fields.latitude && fields.latitude.valid}" id="latitude" name="latitude" placeholder="{{ trans('admin.location.columns.latitude') }}">
        <div v-if="errors.has('latitude')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('latitude') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('longitude'), 'has-success': fields.longitude && fields.longitude.valid }">
    <label for="longitude" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.location.columns.longitude') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.longitude" v-validate="'decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('longitude'), 'form-control-success': fields.longitude && fields.longitude.valid}" id="longitude" name="longitude" placeholder="{{ trans('admin.location.columns.longitude') }}">
        <div v-if="errors.has('longitude')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('longitude') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('place_id'), 'has-success': fields.place_id && fields.place_id.valid }">
    <label for="place_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.location.columns.place_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.place_id" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('place_id'), 'form-control-success': fields.place_id && fields.place_id.valid}" id="place_id" name="place_id" placeholder="{{ trans('admin.location.columns.place_id') }}">
        <div v-if="errors.has('place_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('place_id') }}</div>
    </div>
</div>


