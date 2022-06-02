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
              <i class="demo-pli-printer icon-lg"></i> {{ $root.currentPage }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row pad-top">
                <div class="col-md-6 table-toolbar-left form-horizontal">
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
                  <div class="row" style="padding: 10px 15px 15px 10px">
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
                        :options="filteredvendorList"
                        placeholder="Search for Vendor Name (OPTIONAL)"
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
                    <label class="col-lg-3 control-label text-thin">
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
                  </div>
                  <div class="row pad-all" style="padding-left: 10px;">
                    <label class="col-lg-3 control-label text-thin">
                      <h5>
                        <i class="icon-lg demo-pli-calendar-4 icon-fw"></i> Date
                        as of :
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
                        :disabled="!business_unit || !department || !section"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 table-toolbar-right form-horizontal">
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
                      class="col-lg-3 control-label text-thin"
                      style="text-align: right"
                    >
                      <h5>Date To :</h5>
                    </label>
                    <div class="col-lg-6">
                      <input
                        class="form-control"
                        v-model="date2"
                        type="date"
                        name="dateTo"
                        id="dateTo"
                        style="border-radius: 4px"
                      />
                    </div>
                  </div> -->
                  <div class="row" style="padding: 10px 15px 15px 10px">
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
                        :options="filteredCategoryList"
                        placeholder="Search for Item Dept (OPTIONAL)"
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
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-md-3 control-label text-thin">
                      <h5></h5>
                    </label>
                    <div class="col-md-6 pad-all"></div>
                  </div>
                  <!-- <div class="row pad-all">
                    <button
                      class="btn btn-danger btn-rounded pull-right text-thin mar-lft"
                      :disabled="!notFoundItems || notFoundItems == 0"
                      @click="generateBtnEXCEL($event, 'NotFound')"
                    >
                      <i class="demo-pli-printer icon-lg"></i>&nbsp; Items Not
                      Found({{ notFoundItems }})
                    </button>
                    <button
                      class="btn btn-info btn-rounded pull-right text-thin mar-lft"
                      :disabled="!data.data.length"
                      @click="generateBtn($event)"
                    >
                      <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate
                      PDF
                    </button>

                    <button
                      class="btn btn-info btn-rounded pull-right text-thin"
                      :disabled="!data.data.length"
                      @click="generateBtnEXCEL($event, 'CountData')"
                    >
                      <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate
                      Excel
                    </button>
                  </div> -->
                </div>
              </div>
              <div class="row pad-all">
                <!-- <button
                  class="btn btn-danger btn-rounded pull-right text-thin mar-lft"
                  :disabled="!notFoundItems || notFoundItems == 0"
                  @click="generateBtnEXCEL($event, 'NotFound')"
                >
                  <i class="demo-pli-printer icon-lg"></i>&nbsp; Items Not
                  Found({{ notFoundItems }})
                </button> -->
                <div class="btn-group pull-right">
                  <div class="dropdown">
                    <button
                      class="btn btn-danger btn-rounded text-thin mar-lft dropdown-toggle"
                      :disabled="!notFoundItems || notFoundItems == 0"
                      data-toggle="dropdown"
                      type="button"
                      aria-expanded="false"
                    >
                      <i class="demo-pli-printer icon-lg"></i>&nbsp; Items Not
                      Found ({{ notFoundItems }})
                      <i class="dropdown-caret"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" style="">
                      <li class="dropdown-header">Items Not Found</li>
                      <li>
                        <a
                          href="javscript:;"
                          @click="generateBtnEXCEL($event, 'NotFound Excel')"
                        >
                          Generate Excel
                        </a>
                      </li>
                      <li>
                        <a
                          href="javscript:;"
                          @click="generateBtnEXCEL($event, 'NotFound PDF')"
                        >
                          Generate PDF
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <button
                  class="btn btn-info btn-rounded pull-right text-thin mar-lft"
                  :disabled="!data.data.length"
                  @click="generateBtn($event)"
                >
                  <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate PDF
                </button>

                <button
                  class="btn btn-info btn-rounded pull-right text-thin"
                  :disabled="!data.data.length"
                  @click="generateBtnEXCEL($event, 'CountData')"
                >
                  <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate Excel
                </button>
              </div>
              <table class="table table-striped table-vcenter" id="data-table">
                <thead>
                  <tr>
                    <th>Item Code</th>
                    <th>Barcode</th>
                    <th>Description</th>
                    <th>Uom</th>
                    <th>Qty</th>
                    <th>Smallest SKU</th>
                    <th>Conv. Qty</th>
                    <th>Date Scanned</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="13" style="text-align: center;">
                      No data available.
                    </td>
                  </tr>
                  <tr v-for="(data, index) in data.data" :key="index">
                    <td class="text-main text-thin">{{ data.itemcode }}</td>
                    <td class="text-main text-thin">{{ data.barcode }}</td>
                    <td class="text-main text-thin">
                      {{ data.extended_desc }}
                    </td>
                    <td class="text-main text-normal text-center">
                      {{ data.uom }}
                    </td>
                    <td class="text-main text-normal text-center">
                      {{ data.qty }}
                    </td>
                    <td class="text-main text-normal text-center">
                      <span v-if="data.nav_uom">{{ data.nav_uom }}</span>
                      <span v-else>{{ data.uom }}</span>
                    </td>
                    <td class="text-main text-normal text-center">
                      {{ data.total_conv_qty }}
                    </td>
                    <td class="text-main text-thin">
                      {{ data.datetime_scanned }}
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
  </div>
</template>

<script>
import Vue from 'vue'
import { Datetime } from 'vue-datetime'
import { DateTime } from 'luxon'
import 'vue-datetime/dist/vue-datetime.css'
import vSelect from 'vue-select'
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
      searchProducts: null,
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
      vendorList: [],
      filteredvendorList: [],
      vendor: null,
      categoryList: [],
      filteredCategoryList: [],
      category: null,
      forPrintVendor: [],
      forPrintCategory: [],
      countType: null,
      countTypes: ['ANNUAL', 'CYCLICAL'],
      notFoundItems: 0,
      export: []
    }
  },
  components: {
    datetime: Datetime,
    datetime2: Datetime
  },
  watch: {
    date() {
      if (this.business_unit && this.department && this.section) {
        this.getResults()
      }
    },
    date2() {
      this.getResults()
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
      // let value = []
      // newValue.forEach((element, index) => {
      //   value.push(element.vendor_name)
      // })
      // this.forPrintVendor = value.join('|')
      // this.getResults()

      if (newValue?.length == 0) this.vendor = null
      if (newValue) {
        const res = newValue.find(val => val.vendor_name === 'ALL VENDORS')

        if (res) {
          this.filteredvendorList = this.vendorList.filter(
            categ => categ.vendor_name === res.vendor_name
          )

          this.getResults()
        } else {
          this.filteredvendorList = this.vendorList.filter(
            categ => categ.vendor_name !== 'ALL VENDORS'
          )
          let value = []

          newValue.forEach((element, index) => {
            value.push("'" + element.vendor_name + "'")
          })
          this.forPrintVendor = value.join(' , ')
          this.getResults()
        }
      } else {
        this.filteredvendorList = this.vendorList
      }
    },
    category(newValue) {
      // let value = []
      // newValue.forEach((element, index) => {
      //   value.push(element.category)
      // })
      // this.forPrintCategory = value.join('|')
      // this.getResults()

      if (newValue?.length == 0) this.category = null
      if (newValue) {
        const res = newValue.find(val => val.category === 'ALL CATEGORIES')

        if (res) {
          this.filteredCategoryList = this.categoryList.filter(
            categ => categ.category === res.category
          )

          this.getResults()
        } else {
          this.filteredCategoryList = this.categoryList.filter(
            categ => categ.category !== 'ALL CATEGORIES'
          )
          let value = []

          newValue.forEach((element, index) => {
            value.push("'" + element.category + "'")
          })
          this.forPrintCategory = value.join(' , ')
          this.getResults()
        }
      } else {
        this.filteredCategoryList = this.categoryList
      }
    }
  },
  methods: {
    async generateBtnEXCEL(e, reportType) {
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
      // document.location.href = `/reports/appdata/generate`
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      let pass = null,
        report = null
      if (reportType == 'CountData') {
        pass = '/reports/appdata/generateAppDataExcel'
      } else if (reportType == 'NotFound Excel') {
        pass = '/reports/appdata/generateNotFound'
        report = '&report=Excel'
      } else if (reportType == 'NotFound PDF') {
        pass = '/reports/appdata/generateNotFound'
        report = '&report=PDF'
      }
      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
      const { headers, data } = await axios.get(
        pass +
          `?date=${btoa(this.date)}&date2=${btoa(this.date2)}&vendors=${btoa(
            this.forPrintVendor
          )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
            this.department
          }&section=${this.section}&countType=${this.countType}` +
          report,
        {
          responseType: 'blob'
        }
      )
      // return console.log(headers)
      const { 'content-disposition': contentDisposition } = headers
      const [attachment, file] = contentDisposition.split(' ')
      const [key, fileName] = file.split('=')

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement('a')
      link.href = url
      let section = null
      // console.log(fileName)
      this.section ? (section = '-' + this.section) : (section = '')

      let title = 'Actual Count (APP)',
        fileType = '.xlsx'
      if (reportType == 'NotFound Excel') {
        title = 'Items Not Found from Actual Count (APP)'
      } else if (reportType == 'NotFound PDF') {
        title = 'Items Not Found from Actual Count (APP)'
        fileType = '.pdf'
      }
      link.setAttribute(
        'download',
        `${title} as of ${this.date}  ${this.business_unit} ${this.department}${section}${fileType}`
      )
      // console.log(link)
      document.body.appendChild(link)
      link.click()

      thisButton.disabled = false
      thisButton.innerHTML = oldHTML
      Swal.close()
      $.niftyNoty({
        type: 'success',
        icon: 'pli-cross icon-2x',
        message: '<i class="fa fa-check"></i> Generate successful!',
        container: 'floating',
        timer: 5000
      })
    },
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
      // document.location.href = `/reports/appdata/generate`
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'

      // `/reports/appdata/generate?date=${btoa(this.date)}&date2=${btoa(
      //   this.date2
      // )}&vendors=${btoa(this.forPrintVendor)}&category=${
      //   this.forPrintCategory
      // }&bu=${this.business_unit}&dept=${this.department}&section=${
      //   this.section
      // }&countType=${this.countType}`

      const { headers, data } = await axios.post(
        `/reports/appdata/generate`,
        {
          export: btoa(JSON.stringify(this.export))
        },
        {
          responseType: 'blob'
        }
      )
      // return console.log(headers)
      const { 'content-disposition': contentDisposition } = headers
      const [attachment, file] = contentDisposition.split(' ')
      const [key, fileName] = file.split('=')

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement('a')
      link.href = url
      let section = null
      // console.log(fileName)
      this.section ? (section = '-' + this.section) : (section = '')
      // console.log(fileName)
      link.setAttribute(
        'download',
        `Actual Count (APP) as of ${this.date} ${this.business_unit} ${this.department}${section}.pdf`
      )
      // console.log(link)
      document.body.appendChild(link)
      link.click()

      thisButton.disabled = false
      thisButton.innerHTML = oldHTML
      Swal.close()
      $.niftyNoty({
        type: 'success',
        icon: 'pli-cross icon-2x',
        message: '<i class="fa fa-check"></i> Generate successful!',
        container: 'floating',
        timer: 5000
      })
    },
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
          // .get(`/setup/location/getDept/?bu=${bu.bunit_code}`)
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
      if (search) {
        loading(true)
        this.search2(search, loading, this)
      }
    },
    search2: debounce((search, loading, vm) => {
      if (search.trim().length > 0) {
        axios
          .get(`/uploading/nav_upload/getCategory?category=${search}`)
          .then(({ data }) => {
            vm.filteredCategoryList = data
            loading(false)
          })
          .catch(error => {
            vm.filteredCategoryList = []
            loading(false)
          })
      } else {
        vm.filteredCategoryList = []
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
            vm.filteredvendorList = data
            loading(false)
          })
          .catch(error => {
            vm.filteredvendorList = []
            loading(false)
          })
      } else {
        vm.filteredvendorList = []
        loading(false)
      }
    }, 1000),
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
        this.getVendor(),
        this.getCategory(),
        // this.getBU(),
        this.getCompany()
      ]).then(response => {
        this.vendorList = response[0].data
        this.filteredvendorList = response[0].data
        this.categoryList = response[1].data
        this.filteredCategoryList = response[1].data
        this.companyList = response[2].data
      })
    },
    getFormattedDateToday() {
      return new Date()
        .toJSON()
        .slice(0, 10)
        .replace(/-/g, '-')
    },
    async getNotFound() {
      return await axios.get(
        `/reports/appdata/getNotFound/?date=${btoa(this.date)}&date2=${btoa(
          this.date2
        )}&vendors=${btoa(this.forPrintVendor)}&category=${
          this.forPrintCategory
        }&bu=${this.business_unit}&dept=${this.department}&section=${
          this.section
        }`
      )
    },
    async getCountData(page = 1) {
      let url = `/reports/appdata/getResults/?date=${btoa(
        this.date
      )}&date2=${btoa(this.date2)}&vendors=${btoa(
        this.forPrintVendor
      )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
        this.department
      }&section=${this.section}&countType=${this.countType}&page=`

      return await axios.get(url + page)
    },
    async getExport() {
      let url = `/reports/appdata/getResults/?date=${btoa(
        this.date
      )}&date2=${btoa(this.date2)}&vendors=${btoa(
        this.forPrintVendor
      )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
        this.department
      }&section=${this.section}&countType=${this.countType}&forExport=true`

      return await axios.get(url)
    },
    getResults() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.vendor &&
        this.category
      )
        Promise.all([
          this.getCountData(),
          this.getNotFound(),
          this.getExport()
        ]).then(response => {
          // this.export = []
          this.data = response[0].data
          this.total_result = response[0].data.total
          this.notFoundItems = response[1].data.total
          this.export = response[2].data
        })
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.name = this.$root.authUser.name
    // this.getResults()
    this.getResults2()
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
  font-size: 15px;
}
</style>
