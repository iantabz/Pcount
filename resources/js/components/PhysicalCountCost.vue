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
                        :options="filteredcategoryList"
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
                  <!-- <div class="row pad-all">
                    <button
                      class="btn btn-info btn-rounded pull-right text-thin mar-lft"
                      :disabled="!data.data.length"
                      @click="generateBtn($event)"
                    >
                      <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate
                      Report
                    </button>

                    <button
                      class="btn btn-info btn-rounded pull-right text-thin"
                      :disabled="!data.data.length"
                      @click="generateBtnEXCEL($event)"
                    >
                      <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate
                      Excel
                    </button>
                  </div> -->
                </div>
              </div>
              <div class="row pad-all">
                <button
                  class="btn btn-info btn-rounded pull-right text-thin mar-lft"
                  :disabled="!data.data.length"
                  @click="generateBtn($event)"
                >
                  <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate Report
                </button>

                <button
                  class="btn btn-info btn-rounded pull-right text-thin"
                  :disabled="!data.data.length"
                  @click="generateBtnEXCEL($event)"
                >
                  <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate Excel
                </button>
              </div>
              <table class="table table-striped table-vcenter" id="data-table">
                <thead>
                  <tr>
                    <th rowspan="2">Item Code</th>
                    <th rowspan="2">Barcode</th>
                    <th rowspan="2">Description</th>
                    <th rowspan="2">Uom</th>
                    <th rowspan="2">Count</th>
                    <th rowspan="2">Smallest SKU</th>
                    <th rowspan="2">Nav Qty</th>
                    <th rowspan="2">Rack</th>
                    <th
                      colspan="2"
                      class="text-center"
                      style="vertical-align: middle;"
                    >
                      Cost
                    </th>
                    <th class="text-center" rowspan="2">Total</th>
                  </tr>
                  <tr>
                    <th>
                      w/ VAT
                    </th>
                    <th>
                      w/o VAT
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="13" style="text-align: center;">
                      No data available.
                    </td>
                  </tr>
                  <tr v-for="(data, index) in data.data" :key="index">
                    <td class="text-main text-normal">{{ data.itemcode }}</td>
                    <td class="text-main text-normal">{{ data.barcode }}</td>
                    <td class="text-main text-normal">
                      {{ data.extended_desc }}
                    </td>
                    <td class="text-main text-normal">{{ data.uom }}</td>
                    <td class="text-main text-normal">{{ data.qty }}</td>
                    <td class="text-main text-normal">{{ data.nav_uom }}</td>
                    <td class="text-main text-normal">
                      {{ data.total_conv_qty }}
                    </td>
                    <td class="text-main text-normal">{{ data.rack_desc }}</td>
                    <td class="text-main text-normal text-right">
                      {{
                        data.cost_vat ? '₱ '.data.cost_vat : '-' | numberFormat
                      }}
                    </td>
                    <td class="text-main text-normal text-right">
                      ₱ {{ data.cost_novat | numberFormat }}
                    </td>
                    <td class="text-main text-normal text-right">
                      <!-- ₱ {{ data.tot_novat | numberFormat }} -->
                      ₱
                      {{
                        computeVariance(data.cost_novat, data.total_conv_qty)
                          | numberFormat
                      }}
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
      filteredcategoryList: [],
      category: null,
      forPrintVendor: [],
      forPrintCategory: [],
      countType: null,
      countTypes: ['ANNUAL', 'CYCLICAL']
    }
  },
  components: {
    datetime: Datetime,
    datetime2: Datetime
  },
  watch: {
    date() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.vendor &&
        this.category
      )
        this.getResults()
    },
    date2() {
      // this.getResults()
    },
    business_unit() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.vendor &&
        this.category
      )
        this.getResults()
    },
    department() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.vendor &&
        this.category
      )
        this.getResults()
    },
    section() {
      if (
        this.business_unit &&
        this.department &&
        this.section &&
        this.vendor &&
        this.category
      )
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

        console.log(res)
        if (res) {
          this.filteredvendorList = this.vendorList.filter(
            categ => categ.vendor_name === res.vendor_name
          )

          if (
            this.business_unit &&
            this.department &&
            this.section &&
            this.vendor &&
            this.category
          ) {
            this.getResults()
          }
        } else {
          this.filteredvendorList = this.vendorList.filter(
            categ => categ.vendor_name !== 'ALL VENDORS'
          )
          let value = []

          newValue.forEach((element, index) => {
            value.push("'" + element.vendor_name + "'")
          })
          this.forPrintVendor = value.join(' , ')
          if (
            this.business_unit &&
            this.department &&
            this.section &&
            this.vendor &&
            this.category
          ) {
            this.getResults()
          }
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
          this.filteredcategoryList = this.categoryList.filter(
            categ => categ.category === res.category
          )

          if (
            this.business_unit &&
            this.department &&
            this.section &&
            this.vendor &&
            this.category
          ) {
            this.getResults()
          }
        } else {
          this.filteredcategoryList = this.categoryList.filter(
            categ => categ.category !== 'ALL CATEGORIES'
          )
          let value = []

          newValue.forEach((element, index) => {
            value.push("'" + element.category + "'")
          })
          this.forPrintCategory = value.join(' , ')
          if (
            this.business_unit &&
            this.department &&
            this.section &&
            this.vendor &&
            this.category
          ) {
            this.getResults()
          }
        }
      } else {
        this.filteredcategoryList = this.categoryList
      }
    }
  },
  methods: {
    async generateBtnEXCEL(e) {
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
      const { headers, data } = await axios.get(
        `/reports/pcount_cost/generatePcountCostExcel?date=${btoa(
          this.date
        )}&date2=${btoa(this.date2)}&vendors=${btoa(
          this.forPrintVendor
        )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
          this.department
        }&section=${this.section}&countType=${this.countType}`,
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

      link.setAttribute(
        'download',
        `Inventory Valuation per Navision as of ${this.date} ${this.business_unit} ${this.department}${section}.xlsx`
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

      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
      const { headers, data } = await axios.get(
        `/reports/pcount_cost/generatePcountCost?date=${btoa(
          this.date
        )}&date2=${btoa(this.date2)}&vendors=${btoa(
          this.forPrintVendor
        )}&category=${this.forPrintCategory}&company=${this.company}&bu=${
          this.business_unit
        }&dept=${this.department}&section=${this.section}`,
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
      // console.log(fileName)
      link.setAttribute(
        'download',
        `Inventory Valuation per Navision as of ${this.date} ${this.business_unit} ${this.department}${section}.pdf`
      )
      console.log(link)
      document.body.appendChild(link)
      link.click()

      thisButton.disabled = false
      thisButton.innerHTML = oldHTML
      $.niftyNoty({
        type: 'success',
        icon: 'pli-cross icon-2x',
        message: '<i class="fa fa-check"></i> Generate successful!',
        container: 'floating',
        timer: 5000
      })
      Swal.close()
    },
    computeVariance(cost, qty) {
      let total = 0
      total = parseFloat(cost) * parseFloat(qty)
      console.log(cost, qty, total)
      return total
    },
    departmentSelected(val) {
      this.section = null
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
            vm.filteredcategoryList = data
            loading(false)
          })
          .catch(error => {
            vm.filteredcategoryList = []
            loading(false)
          })
      } else {
        vm.filteredcategoryList = []
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
    getResults(page = 1) {
      let url = null
      url = `/reports/pcount_cost/getResultPcountCost?date=${btoa(
        this.date
      )}&date2=${btoa(this.date2)}&vendors=${btoa(
        this.forPrintVendor
      )}&category=${this.forPrintCategory}&bu=${this.business_unit}&dept=${
        this.department
      }&section=${this.section}&page=`
      if (this.business_unit && this.department && this.section) {
        axios.get(url + page).then(response => {
          this.data = response.data
          this.total_result = response.data.total
        })
      }
    },
    getResults2() {
      Promise.all([
        this.getVendor(),
        this.getCategory(),
        this.getBU(),
        this.getCompany()
      ]).then(response => {
        this.vendorList = response[0].data
        this.filteredvendorList = response[0].data
        this.categoryList = response[1].data
        this.filteredcategoryList = response[1].data
        // this.buList = response[2].data
        this.companyList = response[3].data
      })
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
    getFormattedDateToday() {
      return new Date()
        .toJSON()
        .slice(0, 10)
        .replace(/-/g, '-')
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
  font-size: 14px;
}
</style>
