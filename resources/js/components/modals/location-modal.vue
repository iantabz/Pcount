<!-- @format -->

<template>
  <div>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mdlTitle">Modal Title</h5>
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
            <div class="form-group">
              <label
                style="text-align: right; padding-top: 5px"
                class="col-sm-3 control-label text-main text-semibold"
                for="demo-hor-inputemail"
                >Company</label
              >
              <div class="col-sm-9">
                <select
                  id="company"
                  class="form-control"
                  tabindex="1"
                  style="width: 100%;"
                  @change="companySelected($event)"
                >
                  <option value="">Select</option>
                  <option
                    v-for="opt in companyList"
                    :key="opt.company_code"
                    :value="opt.company_code"
                  >
                    {{ opt.acroname }}
                  </option>
                </select>
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
                <select
                  id="business_unit"
                  class="form-control"
                  tabindex="1"
                  style="width: 100%"
                  @change="buSelected($event)"
                >
                  <option value="">Select</option>
                  <option
                    v-for="opt in buList"
                    :key="opt.bunit_code"
                    :value="opt.bunit_code"
                  >
                    {{ opt.business_unit }}
                  </option>
                </select>
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
                <select
                  id="department"
                  class="form-control"
                  tabindex="1"
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
                </select>
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
                <select
                  id="section"
                  class="form-control"
                  tabindex="1"
                  style="width: 100%"
                >
                  <option value="">Select</option>
                  <option
                    v-for="opt in sectionList"
                    :key="opt.section_code"
                    :value="opt.section_code"
                  >
                    {{ opt.section_name }}
                  </option>
                </select>
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
                <input
                  type="text"
                  placeholder="User"
                  id="user"
                  class="form-control"
                  style="font-size: 12px"
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
                <input
                  type="text"
                  placeholder="Audit"
                  id="audit"
                  class="form-control"
                  style="font-size: 12px"
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
                />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary">
            Close
          </button>
          <button type="button" class="btn btn-primary">
            <span>Save</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      companyList: {},
      company: null,
      buList: [],
      business_unit: null,
      deptList: [],
      department: null,
      sectionList: [],
      section: null
    }
  },
  methods: {
    // sectionSelected(e) {
    //   const sectionSelected = e.target.value
    //   const section = this.sectionList.filter(
    //     sm => sm.section_code == sectionSelected
    //   )[0]
    //   this.section = section.section_code
    // },
    departmentSelected(e) {
      const departmentSelected = e.target.value
      const department = this.deptList.filter(
        sm => sm.dept_code == departmentSelected
      )[0]
      this.department = department.dept_name

      // axios
      //   .get(
      //     `/uploading/nav_upload/getSection/?code=${this.company}&bu=${this.bu}&dept=${this.department}`
      //   )
      //   .then(response => {
      //     this.sectionList = response.data
      //     // setTimeout(() => {}, 100)
      //   })
      //   .catch(response => {
      //     console.log('error')
      //   })
    },
    buSelected(e) {
      const buSelected = e.target.value
      const bu = this.buList.filter(sm => sm.bunit_code == buSelected)[0]
      const company = this.companyList.filter(e => e.company == this.company)[0]
      this.business_unit = bu.business_unit

      axios
        .get(
          `/uploading/nav_upload/getDept/?code=${company.company_code}&bu=${bu.bunit_code}`
        )
        .then(response => {
          this.deptList = response.data
          // setTimeout(() => {}, 100)
        })
        .catch(response => {
          console.log('error')
        })
    },
    companySelected(e) {
      console.log(e)

      const companySelected = e.target.value
      const comp = this.companyList.filter(
        sm => sm.company_code == companySelected
      )[0]
      this.company = comp.company

      axios
        .get(`/uploading/nav_upload/getBU/?code=${comp.company_code}`)
        .then(response => {
          this.buList = response.data
          // setTimeout(() => {}, 100)
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
    }
  },
  mounted() {
    this.getCompany()
    setTimeout(() => {
      $('#company, #business_unit, #department, #section').select2({
        dropdownParent: $('#demo-default-modal'),
        placeholder: 'Select a state',
        allowClear: true
      })
    }, 1000)
  }
}
</script>

<style lang="scss" scoped></style>
