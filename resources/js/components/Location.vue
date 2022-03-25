<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <div class="panel-body">
          <div class="panel-heading pad-all">
            <h3
              class="panel-heading bord-btm text-thin"
              style="font-size: 20px;/* padding: 15px 0px 0px 25px; */"
            >
              <i class="demo-pli-map-2 icon-lg"></i> {{ $root.currentPage }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row pad-top">
                <div class="col-md-6 table-toolbar-left form-horizontal">
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Company :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        :options="companyList"
                        :reduce="companyList => companyList.acroname"
                        label="acroname"
                        v-model="company"
                        placeholder="Company"
                        @input="companySelected($event)"
                      ></v-select>
                    </div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Business Unit :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        v-model="business_unit"
                        label="business_unit"
                        :options="buList"
                        placeholder="Search for Business Unit"
                        :reduce="buList => buList.business_unit"
                        @input="buSelected($event)"
                      >
                      </v-select>
                    </div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Department :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        :options="deptList"
                        :reduce="deptList => deptList.dept_name"
                        label="dept_name"
                        v-model="department"
                        placeholder="Department"
                        :disabled="!business_unit"
                        @input="departmentSelected($event)"
                      >
                      </v-select>
                    </div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px"></div>
                </div>
                <div class="col-md-6 table-toolbar-right form-horizontal">
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-3 control-label text-bold">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-3 control-label text-bold">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Section :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        :options="sectionList"
                        :reduce="sectionList => sectionList.section_name"
                        label="section_name"
                        v-model="section"
                        placeholder="Section"
                        :disabled="!department"
                      ></v-select>
                    </div>
                  </div>
                  <!-- <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-3 control-label text-bold">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div> -->
                  <div class="row pad-all">
                    <button
                      class="btn btn-info btn-rounded mar-lft"
                      :disabled="
                        !company || !business_unit || !department || !section
                      "
                      data-target="#demo-default-modal"
                      data-toggle="modal"
                    >
                      <i class="demo-pli-add-user-star icon-lg"></i> New
                      Location
                    </button>
                  </div>
                </div>
              </div>
              <table class="table table-striped table-vcenter" id="data-table">
                <thead>
                  <tr>
                    <th class="text-main text-center">Location ID</th>
                    <th class="text-main text-center">Inventory Clerk</th>
                    <th class="text-main text-center">IAD Audit</th>
                    <th class="text-main text-center">Rack Description</th>
                    <th class="text-main text-center">Date Added</th>
                    <th class="text-main text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="6" style="text-align: center;">
                      No data available.
                    </td>
                  </tr>
                  <tr v-for="(data, index) in data.data" :key="index">
                    <td class="text-main text-normal">
                      {{ data.location_id }}
                    </td>
                    <td class="text-main text-normal">
                      {{ data.app_users.name }}
                    </td>
                    <td class="text-main text-normal">
                      {{ data.app_audit.name }}
                    </td>
                    <td class="text-main text-normal">
                      {{ data.rack_desc }}
                    </td>
                    <td class="text-main text-normal">
                      {{ data.date_added | formatDate }}
                    </td>
                    <td>
                      <button
                        @click="editBtn(data)"
                        class="btn btn-info btn-xs"
                      >
                        <i class="demo-pli-gear icon-lg icon-fw"></i>
                      </button>
                      <!-- <button
                        @click="assignBtn(data)"
                        class="btn btn-info btn-xs"
                      >
                        <i class="demo-pli-gear icon-lg icon-fw"></i>
                      </button> -->
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    Showing {{ data.from }} to {{ data.to }} of
                    {{ data.total }} entries
                    <!-- <span v-if="searchProducts && !date"
                      >(Filtered from {{ total_result }} total entries)</span
                    >
                    <span v-if="searchProducts && date"
                      >(Filtered from {{ total_result }} total entries)</span
                    > -->
                  </div>
                  <div class="col-md-6">
                    <div class="text-right">
                      <pagination
                        style="margin: 0 0 20px 0"
                        :limit="1"
                        :show-disabled="true"
                        :data="data"
                        @pagination-change-page="getResults"
                      ></pagination>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="demo-default-modal"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
      data-keyboard="false"
      data-backdrop="static"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mdlTitle">Location Information</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="closeBtn"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-horizonal panel-body form-padding">
              <!-- <div class="form-group mar-btm">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="demo-hor-inputemail"
                  >Company</label
                >
                <div class="col-sm-9">
                  <v-select
                    :options="companyList"
                    :reduce="companyList => companyList.acroname"
                    label="acroname"
                    v-model="company"
                    placeholder="Company"
                  ></v-select>
                  <small
                    class="help-block text-danger"
                    v-if="locationForm.errors.has('company')"
                    v-html="locationForm.errors.get('company')"
                  />
                </div>
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="demo-hor-inputemail"
                  >Business Unit</label
                >
                <div class="col-sm-9">
                  <v-select
                    :options="buList"
                    :reduce="buList => buList.business_unit"
                    label="business_unit"
                    v-model="business_unit"
                    placeholder="Business Unit"
                  ></v-select>
                  <small
                    class="help-block text-danger"
                    v-if="locationForm.errors.has('business_unit')"
                    v-html="locationForm.errors.get('business_unit')"
                  />
                </div>
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="demo-hor-inputemail"
                  >Department</label
                >
                <div class="col-sm-9">
                  <v-select
                    :options="deptList"
                    :reduce="deptList => deptList.dept_name"
                    label="dept_name"
                    v-model="department"
                    placeholder="Department"
                  ></v-select>
                  <small
                    class="help-block text-danger"
                    v-if="locationForm.errors.has('department')"
                    v-html="locationForm.errors.get('department')"
                  />
                </div>
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="demo-hor-inputemail"
                  >Section</label
                >
                <div class="col-sm-9">
                  <v-select
                    :options="sectionList"
                    :reduce="sectionList => sectionList.section_name"
                    label="section_name"
                    v-model="section"
                    placeholder="Section"
                  ></v-select>
                  <small
                    class="help-block text-danger"
                    v-if="locationForm.errors.has('section')"
                    v-html="locationForm.errors.get('section')"
                  />
                </div>
              </div> -->
              <div class="form-group mar-btm">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="user"
                  >Inventory Clerk</label
                >
                <div class="col-sm-9">
                  <v-select
                    v-model="locationForm.selectedEmp"
                    :filterable="false"
                    @search="retrieveEmp"
                    label="name"
                    :options="employees"
                    placeholder="Search for Employee (Last name)"
                    ><template slot="no-options">
                      <strong>Search for Employee (Last name)</strong>
                    </template>
                    <template slot="option" slot-scope="option">{{
                      `${option.name}`
                    }}</template>
                    <template slot="selected-option" slot-scope="option">{{
                      `${option.name}`
                    }}</template>
                  </v-select>
                  <small
                    class="help-block text-danger"
                    v-if="locationForm.errors.has('selectedEmp')"
                    v-html="locationForm.errors.get('selectedEmp')"
                  />
                  <small class="help-block" style=""
                    ><em
                      >Inventory Clerk is responsible in counting the items
                      during the physical inventory count, and keep track of
                      accurate inventory records by comparing his/her count
                      result to IAD.
                    </em>
                  </small>
                </div>
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="audit"
                  >IAD Audit</label
                >
                <div class="col-sm-9">
                  <v-select
                    v-model.trim="locationForm.selectedAudit"
                    :filterable="false"
                    @search="retrieveAudit"
                    label="name"
                    :options="audit"
                    placeholder="Search for Audit (Last name)"
                    ><template slot="no-options">
                      <strong>Search for Audit (Last name)</strong>
                    </template>
                    <template slot="option" slot-scope="option">{{
                      `${option.name}`
                    }}</template>
                    <template slot="selected-option" slot-scope="option">{{
                      `${option.name}`
                    }}</template>
                  </v-select>
                  <small
                    class="help-block text-danger"
                    v-if="locationForm.errors.has('selectedAudit')"
                    v-html="locationForm.errors.get('selectedAudit')"
                  />
                  <small class="help-block" style=""
                    ><em
                      >IAD is responsible in observing the inventory operation
                      in compliance to the management's instructions for
                      inventory control, verifies the inventory's existence &
                      accuracy of count results.</em
                    >
                  </small>
                </div>
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="location"
                  >Rack Description</label
                >
                <div class="col-sm-9" style="margin-bottom: 10px;">
                  <input
                    type="text"
                    placeholder="Location"
                    id="location"
                    class="form-control"
                    style="font-size: 12px"
                    v-model.trim="locationForm.rack_desc"
                  />
                  <small
                    class="help-block text-danger"
                    v-if="locationForm.errors.has('rack_desc')"
                    v-html="locationForm.errors.get('rack_desc')"
                  />
                </div>
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="location"
                  >Filter by Category</label
                >
                <div class="col-sm-9">
                  <v-select
                    v-model.trim="category"
                    :filterable="false"
                    @search="retrieveCategory"
                    label="category"
                    :options="categoryList"
                    placeholder="Search for Category"
                    multiple
                    ><template slot="no-options">
                      <strong>Search for Category</strong>
                    </template>
                    <template slot="option" slot-scope="option">{{
                      `${option.category}`
                    }}</template>
                    <template slot="selected-option" slot-scope="option">{{
                      `${option.category}`
                    }}</template>
                  </v-select>
                  <!-- <small
                    class="help-block text-danger"
                    v-if="locationForm.errors.has('rack_desc')"
                    v-html="locationForm.errors.get('rack_desc')"
                  /> -->
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="closeBtn"
              data-dismiss="modal"
              aria-label="Close"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              :disabled="locationForm.busy"
              @click="submitBtn()"
            >
              <span v-if="!locationForm.location_id">Save</span>
              <span v-else>Save Changes</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import { Datetime } from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'
import { DateTime } from 'luxon'
import vSelect from 'vue-select'

import Modal from './modals/location-modal.vue'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'
import Form from 'vform'
Vue.component('pagination', require('laravel-vue-pagination'))
Vue.component('v-select', vSelect)
export default {
  data() {
    return {
      data: {
        data: [],
        current_page: null,
        from: null,
        to: null,
        total: null,
        per_page: null
      },
      name: null,
      date: this.getFormattedDateToday(),
      date2: this.getFormattedDateToday(),
      total_result: null,
      companyList: [],
      company: null,
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null,
      showModal: null,
      employees: [],
      audit: [],
      categoryList: [],
      category: null,
      vendorList: [],
      vendor: null,
      locationForm: new Form({
        location_id: null,
        selectedEmp: null,
        selectedAudit: null,
        company: null,
        business_unit: null,
        department: null,
        section: null,
        rack_desc: null,
        forPrintCategory: [],
        forPrintVendor: []
      }),
      forPrintCategory: [],
      forPrintVendor: []
    }
  },
  components: {
    datetime: Datetime,
    Modal
  },
  watch: {
    date() {
      if (this.business_unit && this.department && this.section) {
        this.getResults()
      }
    },
    business_unit() {
      this.getResults()
    },
    department() {
      this.getResults()
    },
    section() {
      this.getResults()
    },
    vendor(newValue) {
      let value = []
      newValue.forEach((element, index) => {
        value.push(element.vendor_name)
      })
      this.forPrintVendor = value.join("' , '")
    },
    category(newValue) {
      if (newValue) {
        let value = []
        newValue.forEach((element, index) => {
          value.push(element.category)
        })
        this.forPrintCategory = value.join("' , '")
      }
    }
  },
  methods: {
    retrieveCategory(search, loading) {
      loading(true)
      this.search2(search, loading, this)
    },
    search2: debounce((search, loading, vm) => {
      if (search.trim().length > 0) {
        axios
          .get(`/uploading/nav_upload/getCategory?category=${search}`)
          .then(({ data }) => {
            vm.categoryList = data
            loading(false)
          })
          .catch(error => {
            vm.categoryList = []
            loading(false)
          })
      } else {
        vm.categoryList = []
        loading(false)
      }
    }, 1000),
    retrieveVendor(search, loading) {
      loading(true)
      this.search(search, loading, this)
    },
    search: debounce((search, loading, vm) => {
      if (search.trim().length > 0) {
        axios
          .get(`/uploading/nav_upload/getVendor?vendor=${search}`)
          .then(({ data }) => {
            vm.vendorList = data
            loading(false)
          })
          .catch(error => {
            vm.vendorList = []
            loading(false)
          })
      } else {
        vm.vendorList = []
        loading(false)
      }
    }, 1000),
    submitBtn() {
      this.locationForm.company = this.company
      this.locationForm.business_unit = this.business_unit
      this.locationForm.department = this.department
      this.locationForm.section = this.section
      this.locationForm.forPrintCategory = this.forPrintCategory

      this.locationForm
        .post('/setup/location/createLocation')
        .then(({ data, status }) => {
          if (status == 200) {
            this.getResults()
            this.closeBtn()
            $('#demo-default-modal').modal('hide')

            $.niftyNoty({
              type: 'success',
              icon: 'pli-cross icon-2x',
              message: '<i class="fa fa-check"></i> User added successfully!',
              container: 'floating',
              timer: 5000
            })
          }
          if (status == 201) {
            this.getResults()
            this.closeBtn()

            $('#demo-default-modal').modal('hide')
            $.niftyNoty({
              type: 'success',
              icon: 'pli-cross icon-2x',
              message: '<i class="fa fa-check"></i> Update successful!',
              container: 'floating',
              timer: 5000
            })
          }
        })
        .catch(({ response }) => {
          const { status, data } = response
          console.log(status, data)
          $.niftyNoty({
            type: 'danger',
            icon: 'fa fa-exclamation-triangle',
            message: data.message,
            container: 'floating',
            timer: 5000
          })
        })
    },
    assignBtn(data) {
      console.log(data)
      var loc_id = data.location_id
      $('#demo-default-modal').modal('show')
    },
    editBtn(data) {
      // this.getCompany()
      console.log(data)
      // const comp = this.companyList.find(sm => (sm.acroname = data.company))
      var bunit_code = data.business_unit,
        business_unit = data.business_unit,
        dept_code = data.department,
        dept_name = data.department,
        emp_id = data.app_users.emp_id,
        emp_no = data.app_users.emp_no,
        emp_pin = data.app_users.emp_pin,
        name = data.app_users.name,
        position = data.app_users.position

      this.buList.push({
        bunit_code,
        business_unit
      })

      this.deptList.push({
        dept_code,
        dept_name
      })

      this.locationForm.selectedEmp = {
        emp_id,
        emp_no,
        emp_pin,
        name,
        position
      }

      var emp_id = data.app_audit.emp_id,
        emp_no = data.app_audit.emp_no,
        name = data.app_audit.name,
        position = data.app_audit.position

      this.locationForm.selectedAudit = {
        emp_id,
        emp_no,
        emp_pin,
        name,
        position
      }

      if (data.nav_count.byCategory === 'True')
        this.category = data.nav_count.categoryName.split("' , '")

      this.company = data.company
      this.business_unit = data.business_unit
      this.department = data.department
      this.section = data.section
      this.locationForm.rack_desc = data.rack_desc
      this.locationForm.location_id = data.location_id
      $('#demo-default-modal').modal('show')
    },
    closeBtn() {
      this.locationForm.reset()
      this.locationForm.clear()
      // this.company = null
      // this.business_unit = null
      // this.department = null
      // this.section = null
      this.category = null
      this.vendor = null
      this.companyList = []
      this.buList = []
      this.deptList = []
      this.sectionList = []
    },
    retrieveAudit(search, loading) {
      loading(true)
      this.searchAudit(search, loading, this)
    },
    searchAudit: debounce((search, loading, vm) => {
      if (search.trim().length > 0) {
        axios
          .get(`/employee/search?lastname=${search}`)
          .then(({ data }) => {
            vm.audit = data
            loading(false)
          })
          .catch(error => {
            vm.audit = []
            loading(false)
          })
      } else {
        vm.audit = []
        loading(false)
      }
    }, 1000),
    retrieveEmp(search, loading) {
      loading(true)
      this.search(search, loading, this)
    },
    search: debounce((search, loading, vm) => {
      if (search.trim().length > 0) {
        axios
          .get(`/employee/search?lastname=${search}`)
          .then(({ data }) => {
            vm.employees = data
            loading(false)
          })
          .catch(error => {
            vm.employees = []
            loading(false)
          })
      } else {
        vm.employees = []
        loading(false)
      }
    }, 1000),
    departmentSelected(val) {
      const department = this.deptList.filter(sm => sm.dept_name == val)[0]
      const bu = this.buList.filter(
        sm => sm.business_unit == this.business_unit
      )[0]
      const company = this.companyList.find(e => e.acroname == this.company)
      axios
        .get(
          `/uploading/nav_upload/getSection/?code=${company.company_code}&bu=${bu.bunit_code}&dept=${department.dept_code}`
        )
        .then(response => {
          this.sectionList = response.data
        })
        .catch(response => {
          console.log('error')
        })
    },
    buSelected(val) {
      this.department = null
      this.section = null
      if (val) {
        const bu = this.buList.filter(sm => sm.business_unit == val)[0]
        const company = this.companyList.find(e => e.acroname == this.company)
        axios
          .get(
            `/setup/location/getDept/?code=${company.company_code}&bu=${bu.bunit_code}`
          )
          .then(response => {
            this.deptList = response.data
          })
          .catch(response => {
            console.log('error')
          })
      }
    },
    companySelected(val) {
      this.business_unit = null
      this.department = null
      this.section = null
      if (val) {
        const comp = this.companyList.filter(sm => sm.acroname == val)[0]
        axios
          .get(`/uploading/nav_upload/getBU/?code=${comp.company_code}`)
          .then(response => {
            this.buList = response.data
          })
          .catch(response => {
            console.log('error')
          })
      }
    },
    async getBU() {
      return await axios.get('/setup/location/getBU')
    },
    async getCategory() {
      return await axios.get('/uploading/nav_upload/getCategory')
    },
    async getVendor() {
      return await axios.get('/uploading/nav_upload/getVendor')
    },
    async getCompany() {
      return await axios.get('/uploading/nav_upload/getCompany')
    },
    getResults2() {
      Promise.all([
        this.getCompany(),
        this.getCategory(),
        this.getVendor()
      ]).then(response => {
        this.companyList = response[0].data
        this.categoryList = response[1].data
        this.vendorList = response[2].data
      })
    },
    getFormattedDateToday() {
      return new Date()
        .toJSON()
        .slice(0, 10)
        .replace(/-/g, '-')
    },
    getResults(page = 1) {
      let url = null
      // `/setup/location/getResults/?page=`
      url = `/setup/location/getResults/?company=${this.company}&bu=${this.business_unit}&dept=${this.department}&section=${this.section}&page=`
      if (this.business_unit && this.department && this.section) {
        axios.get(url + page).then(response => {
          this.data = response.data
          this.total_result = response.data.total
        })
      }
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.name = this.$root.authUser.name
    this.getResults2()

    setTimeout(() => {
      $('#toggleBtn')
        .niftyOverlay({
          iconClass: 'demo-psi-repeat-2 spin-anim icon-2x',
          desc: 'Please wait...'
        })
        .on('click', function(e) {
          var $el = $(this),
            relTime
          $el.niftyOverlay('show')

          // Do something...

          relTime = setInterval(function() {
            // Hide the screen overlay
            $el.niftyOverlay('hide')

            clearInterval(relTime)
          }, 1000)
        })
    }, 1000)
  }
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}
</style>
