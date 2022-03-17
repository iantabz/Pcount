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
              <i class="demo-pli-male icon-lg"></i>
              {{ $root.currentPage }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row">
                <div class="col-md-6 table-toolbar-left">
                  <!-- <form
                    action="consolidate"
                    @submit.prevent="submitformorder"
                    method="post"
                    enctype="multipart/form-data"
                  >
                    <label class="col-lg-3 control-label">
                      <h5>Filter Date :</h5>
                    </label>
                    <div class="col-lg-4">
                      <datetime
                        v-model="date"
                        value-zone="Asia/Manila"
                        zone="Asia/Manila"
                      ></datetime>
                    </div>
                    <label
                      class="col-lg-2 control-label"
                      style="text-align: right"
                    >
                      <h5>To</h5>
                    </label>
                    <div class="col-lg-3">
                      <datetime2
                        v-model="date2"
                        value-zone="Asia/Manila"
                        zone="Asia/Manila"
                      ></datetime2>
                    </div>
                  </form> -->
                </div>
                <div class="col-md-6 table-toolbar-right">
                  <button
                    class="btn btn-primary btn-rounded pull-right btn-sm"
                    data-target="#demo-default-modal"
                    data-toggle="modal"
                    @click="addBtn()"
                  >
                    <i class="fa fa-plus-circle"></i> New User
                  </button>
                </div>
              </div>
              <table class="table table-striped table-vcenter" id="userTable">
                <thead>
                  <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Company</th>
                    <th>Business Unit</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="7" style="text-align: center;">
                      No data available.
                    </td>
                  </tr>
                  <tr v-for="(data, index) in data.data" :key="index">
                    <td>
                      <img
                        src="img/profile-photos/1.png"
                        alt="Profile Picture"
                        class="img-circle img-sm"
                      />
                    </td>
                    <td>{{ data.name }}</td>
                    <td>{{ data.username }}</td>
                    <td>
                      {{ data.company }}
                    </td>
                    <td>
                      {{ data.business_unit }}
                    </td>
                    <td>
                      {{ data.department }}
                    </td>
                    <td>
                      {{ data.section }}
                    </td>
                    <td>
                      <div class="btn-group dropdown" v-if="data.status == '1'">
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
                              @click="btn_activation(data.id, 0)"
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
                              @click="btn_activation(data.id, 1)"
                              id="toggleBtn"
                              data-target="#userTable"
                              >Active</a
                            >
                          </li>
                        </ul>
                      </div>
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
                    Showing {{ data.from }} to <strong>{{ data.to }}</strong> of
                    <strong> {{ data.total }} entries </strong>
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
      tabindex="-1"
      aria-labelledby="demo-default-modal"
      aria-hidden="true"
      data-keyboard="false"
      data-backdrop="static"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mdlTitle">User Information</h5>
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
            <form
              @submit.prevent="submitform"
              class="panel-body form-horizontal"
            >
              <div class="form-group">
                <label class="col-md-3 control-label" for="demo-text-input"
                  >Name</label
                >
                <div class="col-md-8">
                  <v-select
                    v-model.trim="userForm.name"
                    :filterable="false"
                    @search="retrieveEmp"
                    label="name"
                    :options="employees"
                    placeholder="Search for Employee name (Last name)"
                    ><template slot="no-options">
                      <strong>Search for Employee name (Last name)</strong>
                    </template>
                    <template slot="option" slot-scope="option">{{
                      `${option.name}`
                    }}</template>
                    <template slot="selected-option" slot-scope="option">{{
                      `${option.name}`
                    }}</template>
                  </v-select>
                  <small
                    class="text-danger"
                    v-if="userForm.errors.has('name')"
                    v-html="userForm.errors.get('name')"
                  />
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label" for="demo-text-input"
                  >Company</label
                >
                <div class="col-md-8">
                  <v-select
                    :options="companyList"
                    :reduce="companyList => companyList.acroname"
                    label="acroname"
                    v-model="company"
                    placeholder="Company"
                    @input="companySelected($event)"
                  ></v-select>
                  <small
                    class="text-danger"
                    v-if="userForm.errors.has('company')"
                    v-html="userForm.errors.get('company')"
                  />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="demo-text-input"
                  >Business Unit</label
                >
                <div class="col-md-8">
                  <!-- <select
                    class="demo_select2 form-control"
                    id="b_unit"
                    data-placeholder="Select Business Unit to proceed"
                    :disabled="company == null"
                    @change="buSelected($event)"
                  >
                    <option value="" v-if="!buList.length">Select</option>
                    <option
                      v-for="opt in buList"
                      :key="opt.bunit_code"
                      :value="opt.bunit_code"
                    >
                      {{ opt.business_unit }}
                    </option>
                  </select> -->
                  <v-select
                    :options="buList"
                    :reduce="buList => buList.business_unit"
                    label="business_unit"
                    v-model="business_unit"
                    placeholder="Business Unit"
                    @input="buSelected($event)"
                  ></v-select>
                  <small
                    class="text-danger"
                    v-if="userForm.errors.has('business_unit')"
                    v-html="userForm.errors.get('business_unit')"
                  />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="demo-text-input"
                  >Department</label
                >
                <div class="col-md-8">
                  <!-- <select
                    class="demo_select2 form-control"
                    id="department"
                    data-placeholder="Select Department Unit to proceed"
                    :disabled="business_unit == null"
                    @change="departmentSelected($event)"
                  >
                    <option value="" v-if="!deptList.length">Select</option>
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
                    @input="departmentSelected($event)"
                  ></v-select>
                  <small
                    class="text-danger"
                    v-if="userForm.errors.has('department')"
                    v-html="userForm.errors.get('department')"
                  />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="demo-text-input"
                  >Section</label
                >
                <div class="col-md-8">
                  <v-select
                    :options="sectionList"
                    :reduce="sectionList => sectionList.section_name"
                    label="section_name"
                    v-model="section"
                    placeholder="Section"
                  ></v-select>
                  <small
                    class="help-block text-danger"
                    v-if="userForm.errors.has('section')"
                    v-html="userForm.errors.get('section')"
                  />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="demo-text-input"
                  >Username</label
                >
                <div class="col-md-8">
                  <input
                    type="text"
                    placeholder="Input Username"
                    class="form-control"
                    v-model.trim="userForm.username"
                  />
                  <small
                    class="text-danger"
                    v-if="userForm.errors.has('username')"
                    v-html="userForm.errors.get('username')"
                  />
                </div>
              </div>
              <div class="form-group" v-if="!userForm.id">
                <label class="col-md-3 control-label" for="demo-text-input"
                  >Password</label
                >
                <div class="col-md-8">
                  <input
                    type="password"
                    placeholder="Input password"
                    class="form-control"
                    v-model.trim="userForm.password"
                  />
                  <small
                    class="text-danger"
                    v-if="userForm.errors.has('password')"
                    v-html="userForm.errors.get('password')"
                  />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="demo-text-input"
                  >Usertype</label
                >
                <div class="col-md-8">
                  <v-select
                    :options="userTypes"
                    :reduce="userTypes => userTypes.id"
                    label="usertype"
                    v-model="userForm.usertype_id"
                    placeholder="Usertype"
                  ></v-select>
                  <small
                    class="text-danger"
                    v-if="userForm.errors.has('usertype_id')"
                    v-html="userForm.errors.get('usertype_id')"
                  />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeBtn">
              Close
            </button>
            <button
              type="button"
              @click="submitBtn()"
              class="btn btn-primary"
              :disabled="userForm.busy"
            >
              <span v-if="!userForm.id">Save</span>
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

import Form from 'vform'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'

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
      userForm: new Form({
        id: null,
        name: null,
        username: null,
        password: null,
        company: null,
        business_unit: null,
        department: null,
        section: null,
        usertype_id: null
      }),
      searchProducts: null,
      name: null,
      date: DateTime.local().toString(),
      date2: DateTime.local().toString(),
      total_result: null,
      companyList: [],
      company: null,
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null,
      employees: [],
      userTypes: []
    }
  },

  methods: {
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
    editBtn(data) {
      this.getCompany()
      this.getUserTypes()
      setTimeout(() => {
        // this.userForm.fill(data)
        const company = this.companyList.find(sm => sm.acroname == data.company)
        const utype = this.userTypes.find(ut => ut.id == data.usertype_id)
        this.userForm.id = data.id
        this.userForm.name = data.name
        this.userForm.usertype_id = utype.id
        this.userForm.username = data.username
        this.userForm.company = data.company
        this.userForm.business_unit = data.business_unit
        this.userForm.department = data.department
        this.userForm.section = data.section

        // var bunit_code = data.business_unit,
        //   business_unit = data.business_unit,
        //   dept_code = data.department,
        //   dept_name = data.department

        // this.buList.push({
        //   bunit_code,
        //   business_unit
        // })

        // this.deptList.push({
        //   dept_code,
        //   dept_name
        // })

        this.company = data.company
        this.business_unit = data.business_unit
        this.department = data.department
        this.section = data.section
        $('#demo-default-modal').modal('show')
      }, 1000)
    },
    closeBtn() {
      this.userForm.clear()
      this.userForm.reset()
      this.company = null
      this.business_unit = null
      this.department = null
      this.companyList = []
      this.buList = []
      this.deptList = []
      this.userTypes = []
      this.usertype = null
      $('#demo-default-modal').modal('hide')
    },
    submitBtn() {
      this.userForm.company = this.company
      this.userForm.business_unit = this.business_unit
      this.userForm.department = this.department
      this.userForm.section = this.section

      this.userForm
        .post('/setup/users/createUser')
        .then(({ data, status }) => {
          if (status == 200) {
            this.getResults()
            this.userForm.clear()
            this.userForm.reset()

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
    // sectionSelected(e) {
    //   const sectionSelected = e.target.value
    //   const section = this.sectionList.filter(
    //     sm => sm.section_code == sectionSelected
    //   )[0]
    //   this.section = section.section_code
    // },
    departmentSelected(val) {
      const department = this.deptList.filter(sm => sm.dept_name == val)[0]
      const bu = this.buList.filter(
        sm => sm.business_unit == this.business_unit
      )[0]
      const company = this.companyList.filter(
        e => e.acroname == this.company
      )[0]

      // console.log(company, bu, department)
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
      // const buSelected = e.target.value
      const bu = this.buList.filter(sm => sm.business_unit == val)[0]
      const company = this.companyList.filter(
        e => e.acroname == this.company
      )[0]
      this.department = null

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
    },
    companySelected(val) {
      // const companySelected = e.target.value
      const comp = this.companyList.filter(sm => sm.acroname == val)[0]
      this.business_unit = null

      axios
        .get(`/uploading/nav_upload/getBU/?code=${comp.company_code}`)
        .then(response => {
          this.buList = response.data
        })
        .catch(response => {
          console.log('error')
        })
    },
    addBtn() {
      this.getCompany()
      this.getUserTypes()
    },
    getUserTypes() {
      axios
        .get('/setup/users/getUsertypes')
        .then(response => {
          this.userTypes = response.data
        })
        .catch(response => {
          console.log('error')
        })
    },
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
    getResults(page = 1) {
      let url = `/setup/users/getResults/?page=`

      axios.get(url + page).then(response => {
        this.data = response.data
        this.total_result = response.data.total
      })
    },
    btn_activation(id, status) {
      axios
        .post('/setup/users/toggleStatus', { id, status })
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
    // this.name = this.$root.authUser.name
    this.getResults()

    // $('#container').css('position', 'relative')

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
