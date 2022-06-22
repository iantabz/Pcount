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
              <i class="demo-pli-upload-to-cloud icon-lg"></i>
              {{ $root.currentPage }}
            </h3>
          </div>
          <div class="row pad-top">
            <div class="col-lg-6 table-toolbar-left form-horizontal pad-top">
              <div class="row" style="padding: 10px 15px 15px 10px">
                <label
                  class="col-md-3 control-label text-thin"
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
                  class="col-md-3 control-label text-thin"
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
                  class="col-md-3 control-label text-thin"
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
              <!-- <div class="row" style="padding: 10px 15px 15px 10px">
                <label
                  class="col-md-3 control-label text-thin"
                  style="text-align: right"
                >
                  <h5>Vendor Name :</h5></label
                >
                <div class="col-md-6">
                  <v-select
                    v-model="vendor"
                    :filterable="false"
                    @search="retrieveVendor"
                    label="vendor_name"
                    :options="vendorList"
                    placeholder="Search for Vendor Name"
                    multiple
                    ><template slot="no-options">
                      <strong>Search for Vendor Name</strong>
                    </template>
                    <template slot="option" slot-scope="option">{{
                      `${option.vendor_name}`
                    }}</template>
                    <template slot="selected-option" slot-scope="option">{{
                      `${option.vendor_name}`
                    }}</template>
                  </v-select>
                </div>
              </div>
              <div class="row pad-all" style="padding-left: 10px;">
                <label class="col-lg-3 control-label text-bold">
                  <h5>
                    <i class="icon-lg demo-pli-file-edit icon-fw"></i>
                    Count Type :
                  </h5>
                </label>
                <div class="col-lg-6">
                  <v-select
                    :options="countTypes"
                    label="countTypes"
                    v-model="countType"
                    placeholder="Count Type"
                  ></v-select>
                </div>
              </div> -->
              <div class="row pad-all" style="padding-left: 10px;">
                <label class="col-lg-3 control-label text-thin">
                  <h5>
                    <i class="icon-lg demo-pli-calendar-4 icon-fw"></i> Batch
                    Date :
                  </h5>
                </label>
                <div class="col-lg-6">
                  <input
                    class="form-control"
                    v-model="date"
                    type="date"
                    name="dateFrom"
                    id="dateFrom"
                    style="border-radius: 4px"
                  />
                </div>
              </div>
            </div>
            <div
              class="col-lg-6 table-toolbar-right form-horizontal pad-top"
              style="text-align: left"
            >
              <div class="row" style="padding: 10px 15px 15px 10px">
                <label class="col-md-3 control-label text-thin">
                  <h5></h5>
                </label>
                <div class="col-md-6 pad-all"></div>
              </div>
              <div class="row" style="padding: 10px 15px 15px 10px">
                <label class="col-md-3 control-label text-thin">
                  <h5></h5>
                </label>
                <div class="col-md-6 pad-all"></div>
              </div>
              <div class="row" style="padding: 10px 15px 15px 10px">
                <label
                  class="col-md-3 control-label text-thin"
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
                <label
                  class="col-md-3 control-label text-thin"
                  style="text-align: right"
                >
                  <h5>By Dept :</h5></label
                >
                <div class="col-md-6">
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
                </div>
              </div> -->
              <!-- <div class="row" style="padding: 10px 15px 15px 10px">
                <label
                  class="col-md-3 control-label text-thin"
                  style="text-align: right"
                >
                  <h5>Date To :</h5></label
                >
                <div class="col-md-6">
                  <input
                    class="form-control"
                    v-model="date2"
                    type="date"
                    name="dateTo"
                    id="dateTo"
                  />
                </div>
              </div> -->
            </div>
          </div>
          <form id="unpostedUpload" class="dropzone" enctype="multipart/form-data">
            <input id="_token" type="hidden" name="_token" />

            <input type="hidden" :value="business_unit" name="business_unit" />
            <input type="hidden" :value="department" name="department" />
            <input type="hidden" :value="section" name="section" />
            <input type="hidden" :value="countType" name="countType" />
            <input type="hidden" :value="date" name="date" />
            <!-- <input id="date2" type="hidden" :value="date2" name="date2" /> -->
            <!-- <input type="hidden" :value="vendor" name="vendor" /> -->
            <!-- <input type="hidden" :value="category" name="category" /> -->
            <div class="dz-default dz-message">
              <div class="dz-icon">
                <i class="demo-pli-upload-to-cloud icon-5x"></i>
              </div>
              <div>
                <span class="dz-text">Click and select to upload</span>
                <p class="text-sm text-muted">or Select file manually</p>
              </div>
            </div>
            <div class="fallback">
              <input name="file" type="file" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from 'vue'
import axios from 'axios'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'

Dropzone.options.unpostedUpload = {
  paramName: 'file', // The name that will be used to transfer the file
  url: '/uploading/unposted/importUnposted',
  // url: '/uploading/masterfiles/importItemMasterfile',
  timeout: 100000000,
  init: function() {
    this.on('addedfile', function(file) {
      Swal.fire({
        html: "Please wait, don't close the browser.",
        title: 'Uploading in progress',
        timerProgressBar: true,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
          Swal.showLoading()
        },
        willClose: () => {}
      }).then(result => {
        if (result.isConfirmed) {
        }
      })
    })
    this.on('success', function(file) {
      Swal.fire({
        icon: 'success',
        title: 'Upload successful!',
        showConfirmButton: false,
        timer: 1000,
        allowOutsideClick: false
      }).then(result => {
        if (result.isConfirmed | result.isDismissed) {
          this.removeFile(file)
        }
      })
    })
    this.on('error', function(file, errors, { status }) {
      if (status === 422) {
        Swal.fire({
          icon: 'error',
          title: `${errors.message} Duplicate entry.`,
          showConfirmButton: false,
          timer: 5000,
          allowOutsideClick: false
        }).then(result => {
          if (result.isConfirmed | result.isDismissed) {
            this.removeFile(file)
          }
        })
      }
      if (status === 500) {
        Swal.fire({
          icon: 'error',
          title: `${errors.message}`,
          showConfirmButton: false,
          timer: 5000,
          allowOutsideClick: false
        }).then(result => {
          if (result.isConfirmed | result.isDismissed) {
            this.removeFile(file)
          }
        })
      }
    })
  }
}

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
      total_result: null,
      companyList: [],
      company: null,
      vendorList: [],
      vendor: null,
      categoryList: [],
      category: null,
      date: this.getFormattedDateToday(),
      date2: this.getFormattedDateToday(),
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null,
      countType: null,
      countTypes: ['ANNUAL', 'CYCLICAL']
    }
  },
  watch: {
    vendor(newValue, oldValue) {
      let value = []
      newValue.forEach((element, index) => {
        value.push(element.vendor_name)
      })

      $('#vendor').remove()
      $('#_token').after(
        `<input id="vendor" type="hidden" value="'${value.join(
          "' , '"
        )}'" name="vendor" />`
      )
    },
    category(newValue) {
      let value = []
      newValue.forEach((element, index) => {
        value.push(element.category)
      })

      $('#category').remove()
      $('#_token').after(
        `<input id="category" type="hidden" value="'${value.join(
          "' , '"
        )}'" name="category" />`
      )
    }
  },
  methods: {
    departmentSelected(val) {
      const department = this.deptList.filter(sm => sm.dept_name == val)[0]
      const bu = this.buList.filter(
        sm => sm.business_unit == this.business_unit
      )[0]
      const company = this.companyList.find(e => e.acroname == this.company)
      axios
        .get(
          // `/setup/location/getSection/?bu=${bu.bunit_code}&dept=${department.dept_code}`
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
            // `/setup/location/getDept/?bu=${bu.bunit_code}`
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
      // console.log(val)
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
    getResults() {
      Promise.all([
        this.getVendor(),
        this.getCategory(),
        this.getBU(),
        this.getCompany()
      ]).then(response => {
        this.vendorList = response[0].data
        this.categoryList = response[1].data
        this.buList = response[2].data
        this.companyList = response[3].data
      })
    },
    async getCategory() {
      return await axios.get('/uploading/nav_upload/getCategory')
    },
    async getVendor() {
      return await axios.get('/uploading/nav_upload/getVendor')
    },
    async getBU() {
      return await axios.get('/setup/location/getBU')
    },
    async getCompany() {
      return await axios.get('/uploading/nav_upload/getCompany')
    },
    setCSRFToken() {
      document.getElementById('_token').value = document.head.querySelector(
        'meta[name="csrf-token"]'
      ).content
    },
    async uploadFiles() {
      const uri = ''
    },
    getFormattedDateToday() {
      return new Date()
        .toJSON()
        .slice(0, 10)
        .replace(/-/g, '-')
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.setCSRFToken()
    this.getResults()
  }
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table > tbody > tr:hover {
  background-color: rgb(2 2 2 / 5%);
}

h5 {
  font-size: 14px;
}
</style>
