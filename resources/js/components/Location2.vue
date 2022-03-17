<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <div class="panel-body">
          <div class="panel-heading pad-all">
            <h3
              class="panel-heading text-thin"
              style="font-size: 20px;/* padding: 15px 0px 0px 25px; */"
            >
              <i class="demo-pli-map-2 icon-lg"></i>
              {{ $root.currentPage }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row">
                <div class="col-md-6 table-toolbar-left"></div>
                <div class="col-md-6 table-toolbar-right">
                  <button
                    class="btn btn-primary btn-rounded pull-right btn-sm"
                    data-target="#demo-default-modal"
                    data-toggle="modal"
                    @click="addBtn"
                  >
                    <i class="fa fa-plus-circle"></i> New Location
                  </button>
                </div>
              </div>
              <table class="table table-striped" id="userTable">
                <thead>
                  <tr>
                    <th>Location ID</th>
                    <th>Company</th>
                    <th>Business Unit</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th>Rack Description</th>
                    <th>App User</th>
                    <th>App Audit</th>
                    <th>Date Added</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="13" style="text-align: center;">
                      No data available.
                    </td>
                  </tr>
                  <tr v-for="(data, index) in data.data" :key="index">
                    <td>{{ data.location_id }}</td>
                    <td>{{ data.company }}</td>
                    <td>{{ data.business_unit }}</td>
                    <td>{{ data.department }}</td>
                    <td>{{ data.section }}</td>
                    <td>{{ data.rack_desc }}</td>
                    <td>{{ data.app_users.name }}</td>
                    <td>{{ data.app_audit.name }}</td>
                    <td>{{ data.date_added | formatDate }}</td>
                    <td>
                      <!-- <div
                        class="btn-group dropdown"
                        v-if="data.status == 'Active'"
                      >
                        <button
                          class="btn btn-xs btn-info btn-active-blue dropdown-toggle dropdown-toggle-icon"
                          data-toggle="dropdown"
                          type="button"
                        >
                          Active&nbsp;&nbsp;
                          <i class="dropdown-caret"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li>
                            <a
                              href="javascript:;"
                              style="color: red"
                              @click="
                                btn_activation(data.location_id, 'Inactive')
                              "
                              id="toggleBtn"
                              data-target="#userTable"
                              >Inactive</a
                            >
                          </li>
                        </ul>
                      </div>
                      <div class="btn-group dropdown" v-else>
                        <button
                          class="btn btn-xs btn-danger btn-active-blue dropdown-toggle dropdown-toggle-icon"
                          data-toggle="dropdown"
                          type="button"
                        >
                          Inactive
                          <i class="dropdown-caret"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li>
                            <a
                              href="javascript:;"
                              style="color: blue"
                              @click="
                                btn_activation(data.location_id, 'Active')
                              "
                              id="toggleBtn"
                              data-target="#userTable"
                              >Active</a
                            >
                          </li>
                        </ul>
                      </div> -->
                      <button
                        @click="editBtn(data)"
                        class="btn btn-info btn-xs"
                      >
                        <i class="fa fa-pencil-square-o"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    Showing {{ data.from }} to {{ data.to }} of
                    {{ data.total }} entries
                    <span v-if="searchProducts && !date"
                      >(Filtered from {{ total_result }} total entries)</span
                    >
                    <span v-if="searchProducts && date"
                      >(Filtered from {{ total_result }} total entries)</span
                    >
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
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-horizonal panel-body form-padding">
              <div class="form-group mar-btm">
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
                  <!-- <select
                    id="department"
                    class="form-control"
                    style="width: 100%;"
                    @change="departmentSelected($event)"
                  >
                    <option value="">Select</option>
                    <option
                      v-for="opt in deptList"
                      :key="opt.dept_code"
                      :value="opt.dept_code"
                    >
                      {{ opt.dept_name }}
                    </option>
                  </select> -->
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
                  <!-- <select id="section" class="form-control" style="width: 100%">
                    <option value="">Select</option>
                    <option
                      v-for="opt in sectionList"
                      :key="opt.section_code"
                      :value="opt.section_code"
                    >
                      {{ opt.section_name }}
                    </option>
                  </select> -->
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
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="user"
                  >User</label
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
                </div>
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="audit"
                  >Audit</label
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
                </div>
              </div>
              <div class="form-group pad-top" style="margin-top: 30px">
                <label
                  style="text-align: right; padding-top: 5px"
                  class="col-sm-3 control-label text-main text-semibold"
                  for="location"
                  >Location</label
                >
                <div class="col-sm-9">
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
      searchProducts: null,
      date: DateTime.local().toString(),
      date2: DateTime.local().toString(),
      total_result: null,
      showModal: null,
      companyList: [],
      company: null,
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null,
      employees: [],
      audit: [],
      locationForm: new Form({
        location_id: null,
        selectedEmp: null,
        selectedAudit: null,
        company: null,
        business_unit: null,
        department: null,
        section: null,
        rack_desc: null
      })
    }
  },
  components: {
    datetime: Datetime,
    datetime2: Datetime,
    Modal
  },
  watch: {
    company(val) {
      if (val) {
        setTimeout(() => {
          const comp = this.companyList.find(sm => sm.acroname == this.company)
          axios
            .get(`/uploading/nav_upload/getBU/?code=${comp.company_code}`)
            .then(response => {
              this.buList = response.data
            })
        }, 1000)
      }
    },
    business_unit(val) {
      if (val) {
        setTimeout(() => {
          const buSelected = val
          const bu = this.buList.filter(sm => sm.business_unit == buSelected)[0]
          const company = this.companyList.find(e => e.acroname == this.company)

          axios
            .get(
              `/uploading/nav_upload/getDept/?code=${company.company_code}&bu=${bu.bunit_code}`
            )
            .then(response => {
              this.deptList = response.data
            })
            .catch(response => {
              console.log('error')
            })
        }, 1000)
      }
    },
    department(val) {
      if (val) {
        setTimeout(() => {
          const departmentSelected = val
          const department = this.deptList.filter(
            sm => sm.dept_name == departmentSelected
          )[0]
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
        }, 1000)
      }
    }
  },
  methods: {
    editBtn(data) {
      this.getCompany()
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

      this.company = data.company
      this.business_unit = data.business_unit
      this.department = data.department
      this.section = data.section
      this.locationForm.rack_desc = data.rack_desc
      this.locationForm.location_id = data.location_id
      $('#demo-default-modal').modal('show')
    },
    submitBtn() {
      this.locationForm.company = this.company
      this.locationForm.business_unit = this.business_unit
      this.locationForm.department = this.department
      this.locationForm.section = this.section

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
    closeBtn() {
      this.locationForm.reset()
      this.locationForm.clear()
      this.company = null
      this.business_unit = null
      this.department = null
      this.section = null
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

    getCompany() {
      axios
        .get('/uploading/nav_upload/getCompany')
        .then(response => {
          this.companyList = response.data
        })
        .catch(response => {
          console.log('error')
        })
    },
    addBtn() {
      this.getCompany()
    },
    getResults(page = 1) {
      let url = null
      if (!this.searchProducts) {
        url = `/setup/location/getResults/?page=`
      } else {
        url = `/setup/location/getResults/?date=${btoa(this.date)}&date2=${btoa(
          this.date2
        )}&name=${this.searchProducts}&page=`
      }
      axios.get(url + page).then(response => {
        this.data = response.data
        this.total_result = response.data.total
      })
    },
    btn_activation(id, status) {
      axios
        .post('/setup/location/toggleStatus', { id, status })
        .then(({ data, status }) => {
          if (status == 200) {
            this.getResults()

            $.niftyNoty({
              type: 'success',
              icon: 'pli-cross icon-2x',
              message:
                '<i class="fa fa-check"></i> Status changed successfully!',
              container: 'floating',
              timer: 5000
            })
          }
        })
        .catch(error => {})
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.getResults()

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

<style lang="scss" scoped></style>
