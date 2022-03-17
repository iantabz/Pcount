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

                  <!-- <div class="row" style="padding: 10px 15px 15px 10px">
                    <label class="col-lg-3 control-label text-bold">
                      <h5>Date From :</h5>
                    </label>
                    <div class="col-lg-6">
                      <input
                        class="form-control"
                        v-model="date"
                        type="date"
                        name="dateFrom"
                        id="dateFrom"
                      />
                    </div>
                  </div> -->
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-bold"
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
                    <label
                      class="col-lg-3 control-label text-bold"
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
                      />
                    </div>
                  </div> -->
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-3 control-label text-bold"
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
                  </div>
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <!-- :disabled="!data.data.length" -->
                    <button
                      class="btn btn-info btn-rounded pull-right"
                      @click="generateBtn($event)"
                    >
                      <i class="demo-pli-printer icon-lg"></i>&nbsp; Generate
                      Report
                    </button>
                  </div>
                </div>
              </div>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th
                      rowspan="2"
                      class="text-center"
                      style="vertical-align: middle;"
                    >
                      Item Code
                    </th>
                    <th
                      rowspan="2"
                      class="text-center"
                      style="vertical-align: middle;"
                    >
                      Description
                    </th>
                    <th
                      rowspan="2"
                      class="text-center"
                      style="vertical-align: middle;"
                    >
                      UOM
                    </th>
                    <th
                      colspan="2"
                      class="text-center"
                      style="vertical-align: middle;"
                    >
                      Quantity
                    </th>
                    <th
                      rowspan="2"
                      class="text-center"
                      style="vertical-align: middle;"
                    >
                      Variance
                    </th>
                  </tr>
                  <tr>
                    <th class="text-center" style="vertical-align: middle;">
                      Nav
                    </th>
                    <th class="text-center" style="vertical-align: middle;">
                      P Count App
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
                    <td class="text-main text-normal" style="font-size: 1.1em">
                      {{ data.itemcode }}
                    </td>
                    <td class="text-main text-normal" style="font-size: 1.1em">
                      {{ data.extended_desc }}
                    </td>
                    <td
                      class="text-main text-normal text-center"
                      style="font-size: 1.1em"
                    >
                      {{ data.uom }}
                    </td>
                    <td
                      class="text-main text-normal text-center"
                      style="font-size: 1.1em"
                    >
                      {{ Math.trunc(data.nav_qty) }}
                    </td>
                    <td
                      class="text-main text-normal text-center"
                      style="font-size: 1.1em"
                    >
                      {{ data.conversion_qty }}
                    </td>
                    <td
                      class="text-main text-normal text-center"
                      style="font-size: 1.1em"
                    >
                      {{ computeVariance(data.nav_qty, data.conversion_qty) }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    Showing {{ data.from }} to {{ data.to }} of
                    {{ data.total }} entries
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
      name: null,
      date: this.getFormattedDateToday(),
      date2: this.getFormattedDateToday(),
      total_result: null,
      vendorList: [],
      vendor: null,
      categoryList: [],
      category: null,
      forPrintVendor: [],
      forPrintCategory: [],
      companyList: [],
      company: null,
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null
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
    vendor(newValue) {
      let value = []
      newValue.forEach((element, index) => {
        value.push(element.vendor_name)
      })
      this.forPrintVendor = value.join('|')
      // this.getResults()
    },
    category(newValue) {
      let value = []
      newValue.forEach((element, index) => {
        value.push(element.category)
      })
      this.forPrintCategory = value.join('|')
      // this.getResults()
    },
    business_unit() {
      // this.getResults()
    },
    department() {
      // this.getResults()
    },
    section() {
      // this.getResults()
    }
  },
  methods: {
    computeVariance(a, b) {
      let variance = 0
      if (a < 0) {
        variance = parseFloat(b) + parseFloat(a)
      } else {
        variance = parseFloat(b) - parseFloat(a)
      }
      console.log(variance)
      //  const variance = parseFloat(a) - parseFloat(b)
      return variance
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
      // document.location.href = `/reports/variance_report/generate`
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML

      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'
      const { headers, data } = await axios.get(
        `/reports/variance_report/generate?date=${btoa(this.date)}&date2=${btoa(
          this.date2
        )}&vendors=${btoa(this.forPrintVendor)}&category=${
          this.forPrintCategory
        }&bu=${this.business_unit}&dept=${this.department}&section=${
          this.section
        }`,
        {
          responseType: 'blob'
        }
      )
      // return console.log(headers)
      const { 'content-disposition': contentDisposition } = headers
      const [attachment, file] = contentDisposition.split(' ')
      const [key, fileName] = file.split('=')
      // console.log(fileName)

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement('a')
      link.href = url
      // console.log('download', `${fileName.replace('"', '')}.pdf`)

      link.setAttribute('download', `Variance Report  as of ${this.date}.pdf`)
      // console.log(link)
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
      $.niftyNoty({
        type: 'success',
        icon: 'pli-cross icon-2x',
        message: '<i class="fa fa-check"></i> Generate successful!',
        container: 'floating',
        timer: 5000
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
      url = `/reports/variance_report/getResults/?date=${btoa(
        this.date
      )}&date2=${btoa(this.date2)}&vendors=${this.forPrintVendor}&category=${
        this.forPrintCategory
      }&bu=${this.business_unit}&dept=${this.department}&section=${
        this.section
      }&page=`
      if (this.business_unit && this.department && this.section) {
        axios.get(url + page).then(response => {
          this.data = response.data
          this.total_result = response.data.total
        })
      }
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
    getResults2() {
      Promise.all([
        this.getVendor(),
        this.getCategory(),
        this.getBU(),
        this.getCompany()
      ]).then(response => {
        this.vendorList = response[0].data
        this.categoryList = response[1].data
        // this.buList = response[2].data
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
    this.name = this.$root.authUser.name
    // this.getResults()
    this.getResults2()
  }
}
</script>

<style lang="scss" scoped></style>
