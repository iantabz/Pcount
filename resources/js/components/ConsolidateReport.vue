<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content">
      <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
          <div class="panel panel-body">
            <div class="panel-body">
              <div class="panel-heading mar-btm">
                <h3
                  class="pad-btm bord-btm text-thin"
                  style="font-size: 20px;/* padding: 15px 0px 0px 25px; */"
                >
                  <i class="demo-pli-printer icon-lg"></i>
                  {{ $root.currentPage }}
                </h3>
              </div>
              <div class="row pad-top panel-body">
                <div class="col-md-6 table-toolbar-left form-horizontal">
                  <!-- <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-lg-4 control-label text-bold">
                      <h5>Date From :</h5>
                    </label>
                    <div class="col-lg-7">
                      <input
                        class="form-control"
                        v-model="date"
                        type="date"
                        name="dateFrom"
                        id="dateFrom"
                        style="font-size: 12px; text-align: center; border-radius: 4px;"
                      />
                    </div>
                  </div> -->
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-4 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Company :</h5></label
                    >
                    <div class="col-md-7">
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
                      class="col-md-4 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Business Unit :</h5></label
                    >
                    <div class="col-md-7">
                      <v-select
                        label="business_unit"
                        :options="buList"
                        placeholder="Search for Business Unit"
                        :reduce="buList => buList.business_unit"
                        multiple
                        @input="buSelected($event)"
                      >
                      </v-select>
                    </div>
                  </div>

                  <!-- <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-4 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Department :</h5></label
                    >
                    <div class="col-md-7">
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
                  </div> -->
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-4 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Vendor Name :</h5></label
                    >
                    <div class="col-md-7">
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
                    <label class="col-lg-4 control-label text-bold">
                      <h5>
                        <i class="icon-lg demo-pli-calendar-4 icon-fw"></i> Date
                        as of :
                      </h5>
                    </label>
                    <div class="col-lg-7">
                      <input
                        class="form-control"
                        v-model="date"
                        type="date"
                        name="dateFrom"
                        id="dateFrom"
                        style="border-radius: 4px; text-align: center; font-size: 12px"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 table-toolbar-right form-horizontal">
                  <!-- <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-lg-4 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Date To :</h5>
                    </label>
                    <div class="col-lg-7">
                      <input
                        class="form-control"
                        v-model="date2"
                        type="date"
                        name="dateTo"
                        id="dateTo"
                        style="font-size: 12px; text-align: center; border-radius: 4px;"
                      />
                    </div>
                  </div> -->
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-4 control-label text-bold">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-4 control-label text-bold">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div>
                  <!-- <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-4 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>Section :</h5></label
                    >
                    <div class="col-md-7">
                      <v-select
                        :options="sectionList"
                        :reduce="sectionList => sectionList.section_name"
                        label="section_name"
                        v-model="section"
                        placeholder="Section"
                        :disabled="!department"
                      ></v-select>
                    </div>
                  </div> -->
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-4 control-label text-bold"
                      style="text-align: right"
                    >
                      <h5>By Dept :</h5></label
                    >
                    <div class="col-md-7">
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
                  </div>
                </div>
              </div>
              <div class="panel-body demo-nifty-btn">
                <button
                  class="btn btn-block btn-info"
                  @click="generateBtn($event)"
                  id="generateBtn"
                >
                  <i class="demo-pli-printer icon-lg"></i> Generate Report
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import { Datetime } from 'vue-datetime'
import { DateTime } from 'luxon'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'
Vue.component('v-select', vSelect)
export default {
  data() {
    return {
      date: this.getFormattedDateToday(),
      // date2: this.getFormattedDateToday(),
      vendorList: [],
      vendor: null,
      categoryList: [],
      category: null,
      forPrintVendor: [],
      forPrintCategory: [],
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null,
      companyList: [],
      company: null
    }
  },
  watch: {
    vendor(newValue) {
      let value = []
      newValue.forEach((element, index) => {
        value.push(element.vendor_name)
      })
      console.log(value)
      this.forPrintVendor = value.join('|')
    },
    category(newValue) {
      let value = []
      newValue.forEach((element, index) => {
        value.push(element.category)
      })
      this.forPrintCategory = value.join('|')
    }
  },
  methods: {
    
    async generateBtn(e) {
      Swal.fire({
        html: "Please wait, don't close the browser.",
        title: 'Generating report in progress',
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
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
      const { headers, data } = await axios.get(
        `/reports/consolidate_report/generate?date=${btoa(
          this.date
        )}&date2=${btoa(this.date2)}&vendors=${btoa(
          this.forPrintVendor
        )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
          this.department
        }&section=${this.section}`,
        {
          responseType: 'blob'
          // onDownloadProgress: progressEvent => {

          // }
        }
      )

      // return console.log(headers)

      const { 'content-disposition': contentDisposition } = headers

      const [attachment, file] = contentDisposition.split(' ')

      const [key, fileName] = file.split('=')

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement('a')
      link.href = url
      // link.setAttribute(
      //   'download',
      //   `Consolidated Report as of ${this.date}.pdf`
      // )
      link.setAttribute(
        'download',
        `Consolidated Report as of ${this.date}.xlsx`
      )
      document.body.appendChild(link)
      link.click()

      thisButton.disabled = false
      thisButton.innerHTML = oldHTML
      // $.niftyNoty({
      //   type: 'success',
      //   icon: 'pli-cross icon-2x',
      //   message: '<i class="fa fa-check"></i> Generate successful!',
      //   container: 'floating',
      //   timer: 5000
      // })
      Swal.close()
      Swal.fire({
        icon: 'success',
        title: 'Generate successful!',
        showConfirmButton: false,
        timer: 3500
      })
    },
    getFormattedDateToday() {
      return new Date()
        .toJSON()
        .slice(0, 10)
        .replace(/-/g, '-')
    },
    departmentSelected(val) {
      this.section = null
      const department = this.deptList.filter(sm => sm.dept_name == val)[0]
      const bu = this.buList.filter(
        sm => sm.business_unit == this.business_unit
      )[0]
      let url = null
      if (this.company) {
        const company = this.companyList.find(e => e.acroname == this.company)
        url = `/uploading/nav_upload/getSection/?code=${company.company_code}&bu=${bu.bunit_code}&dept=${department.dept_code}`
      } else {
        url = `/setup/location/getSection/?bu=${bu.bunit_code}&dept=${department.dept_code}`
      }
      axios
        .get(url)
        .then(response => {
          this.sectionList = response.data
        })
        .catch(response => {
          console.log('error')
        })
    },
    buSelected(val) {
      let value = []
      val.forEach(element => {
        value.push(element)
      })
      // console.log(value)
      this.business_unit = value.join('|')

      // this.department = null
      // this.section = null
      // let url = null
      // if (val) {
      //   const bu = this.buList.filter(sm => sm.business_unit == val)[0]
      //   if (this.company) {
      //     const company = this.companyList.find(e => e.acroname == this.company)
      //     url = `/setup/location/getDept/?code=${company.company_code}&bu=${bu.bunit_code}`
      //   } else {
      //     url = `/setup/location/getDept/?bu=${bu.bunit_code}`
      //   }

      //   axios
      //     // .get(`/setup/location/getDept/?bu=${bu.bunit_code}`)
      //     .get(url)
      //     .then(response => {
      //       this.deptList = response.data
      //     })
      //     .catch(response => {
      //       console.log('error')
      //     })
      // }
    },
    companySelected(val) {
      // this.business_unit = null
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
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.getResults()
  }
}
</script>
