<!-- @format -->
<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <div class="panel-body">
          <div class="row mar-top">
            <div class="col-md-12">
              <h3 class="panel-heading text-thin" style="font-size: 20px;">
                INVENTORY COUNT CONSOLIDATION SYSTEM
              </h3>
              <h3 class="text-main text-normal text-2x mar-no">
                Welcome back, {{ name }}!
              </h3>
              <h5 class="text-uppercase text-muted text-normal">
                {{ date | DashboardTime }}
              </h5>
              <hr class="new-section-xs" />
              <div class="row mar-top">
                <div class="col-sm-4">
                  <div class="text-lg">
                    <h4
                      style="
                          font-size: 18px;
                          font-weight: 600;
                      "
                      class="mar-btm"
                    >
                      Setup
                    </h4>
                    <div class="list-group bg-trans mar-no">
                      <router-link to="/location">
                        <a
                          href="javascript:;"
                          class="list-group-item panel-title"
                          ><i class="pci-chevron chevron-right"></i>
                          Location (APP users) setup
                        </a>
                      </router-link>
                      <router-link to="/location">
                        <a
                          href="javascript:;"
                          class="list-group-item panel-title"
                          ><i class="pci-chevron chevron-right"></i> Users setup
                        </a>
                      </router-link>
                      <router-link to="/vendor_masterfile">
                        <a
                          href="javascript:;"
                          class="list-group-item panel-title"
                          ><i class="pci-chevron chevron-right"></i> Vendor
                          Masterfile</a
                        >
                      </router-link>
                      <router-link to="/category">
                        <a
                          href="javascript:;"
                          class="list-group-item panel-title"
                          ><i class="pci-chevron chevron-right"></i> Item
                          Department</a
                        >
                      </router-link>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="text-lg">
                    <h4
                      style="font-size: 18px;font-weight: 600;"
                      class="mar-btm"
                    >
                      Reports
                    </h4>
                    <div class="list-group bg-trans mar-no">
                      <router-link to="/physical_count">
                        <a
                          href="javascript:;"
                          class="list-group-item panel-title"
                          ><i class="pci-chevron chevron-right"></i>
                          Actual Count (APP)
                        </a>
                      </router-link>
                      <router-link to="/physical_count_cost">
                        <a
                          href="javascript:;"
                          class="list-group-item panel-title"
                          ><i class="pci-chevron chevron-right"></i> Actual
                          Count (APP) w/ Cost
                        </a>
                      </router-link>
                      <router-link to="/variance_report">
                        <a
                          href="javascript:;"
                          class="list-group-item panel-title"
                          ><i class="pci-chevron chevron-right"></i> Variance
                          Report</a
                        >
                      </router-link>
                      <router-link to="/variance_report_cost">
                        <a
                          href="javascript:;"
                          class="list-group-item panel-title"
                          ><i class="pci-chevron chevron-right"></i> Variance
                          Report w/ Cost</a
                        >
                      </router-link>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4"></div>
              </div>
            </div>
            <!-- <div class="col-md-7">
              <div
                id="demo-bar-chart"
                style="height: 250px; padding: 0px; position: relative;"
              >
                <canvas
                  class="flot-base"
                  width="699"
                  height="250"
                  style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 699.828px; height: 250px;"
                ></canvas>
                <div
                  class="flot-text"
                  style="position: absolute; inset: 0px; font-size: smaller; color: rgb(84, 84, 84);"
                >
                  <div
                    class="flot-x-axis flot-x1-axis xAxis x1Axis"
                    style="position: absolute; inset: 0px;"
                  >
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; max-width: 55px; top: 234px; left: 73px; text-align: center;"
                    >
                      0
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; max-width: 55px; top: 234px; left: 157px; text-align: center;"
                    >
                      1
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; max-width: 55px; top: 234px; left: 241px; text-align: center;"
                    >
                      2
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; max-width: 55px; top: 234px; left: 326px; text-align: center;"
                    >
                      3
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; max-width: 55px; top: 234px; left: 410px; text-align: center;"
                    >
                      4
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; max-width: 55px; top: 234px; left: 495px; text-align: center;"
                    >
                      5
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; max-width: 55px; top: 234px; left: 579px; text-align: center;"
                    >
                      6
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; max-width: 55px; top: 234px; left: 664px; text-align: center;"
                    >
                      7
                    </div>
                  </div>
                  <div
                    class="flot-y-axis flot-y1-axis yAxis y1Axis"
                    style="position: absolute; inset: 0px;"
                  >
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; top: 221px; left: 18px; text-align: right;"
                    >
                      0
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; top: 177px; left: 6px; text-align: right;"
                    >
                      200
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; top: 133px; left: 6px; text-align: right;"
                    >
                      400
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; top: 88px; left: 6px; text-align: right;"
                    >
                      600
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; top: 44px; left: 6px; text-align: right;"
                    >
                      800
                    </div>
                    <div
                      class="flot-tick-label tickLabel"
                      style="position: absolute; top: 0px; left: 0px; text-align: right;"
                    >
                      1000
                    </div>
                  </div>
                </div>
                <canvas
                  class="flot-overlay"
                  width="699"
                  height="250"
                  style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 699.828px; height: 250px;"
                ></canvas>
                <div class="legend">
                  <div
                    style="position: absolute; width: 57px; height: 51px; top: 13px; right: 13px; background-color: rgb(255, 255, 255); opacity: 0.85;"
                  ></div>
                  <table
                    style="position:absolute;top:13px;right:13px;;font-size:smaller;color:#545454"
                  >
                    <tbody>
                      <tr>
                        <td class="legendColorBox">
                          <div style="border:1px solid #ccc;padding:1px">
                            <div
                              style="width:4px;height:0;border:5px solid rgb(3,169,244);overflow:hidden"
                            ></div>
                          </div>
                        </td>
                        <td class="legendLabel">Students</td>
                      </tr>
                      <tr>
                        <td class="legendColorBox">
                          <div style="border:1px solid #ccc;padding:1px">
                            <div
                              style="width:4px;height:0;border:5px solid rgb(255,179,0);overflow:hidden"
                            ></div>
                          </div>
                        </td>
                        <td class="legendLabel">Parents</td>
                      </tr>
                      <tr>
                        <td class="legendColorBox">
                          <div style="border:1px solid #ccc;padding:1px">
                            <div
                              style="width:4px;height:0;border:5px solid rgb(227,232,238);overflow:hidden"
                            ></div>
                          </div>
                        </td>
                        <td class="legendLabel">Teachers</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <hr class="new-section-xs bord-no" />
              <ul class="list-inline text-center">
                <li><span class="label label-info">354</span> Students</li>
                <li><span class="label label-warning">74</span> Parents</li>
                <li><span class="label label-default">25</span> Teachers</li>
              </ul>
            </div> -->
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3">
          <div class="row">
            <div class="col-sm-3 col-lg-6">
              <!-- <div class="panel panel-primary panel-colorful">
                <div class="pad-all text-center">
                  <span class="text-3x text-thin">{{ total_result }}</span>
                  <p>App Users</p>
                  <i class="demo-psi-male icon-lg"></i>
                </div>
              </div>
              <div class="panel panel-warning panel-colorful">
                <div class="pad-all text-center">
                  <span class="text-3x text-thin">15</span>
                  <p>Users</p>
                  <i class="demo-psi-male icon-lg"></i>
                </div>
              </div> -->
            </div>
            <div class="col-sm-3 col-lg-6">
              <!-- <div class="panel panel-purple panel-colorful">
                <div class="pad-all text-center">
                  <span class="text-3x text-thin">15</span>
                  <p>Users</p>
                  <i class="demo-pli-male"></i>
                </div>
              </div>
              <div class="panel panel-dark panel-colorful">
                <div class="pad-all text-center">
                  <span class="text-3x text-thin">12</span>
                  <p>Reports</p>
                  <i class="demo-psi-receipt-4 icon-lg"></i>
                </div>
              </div> -->
            </div>
            <div class="col-sm-6 col-lg-12">
              <div class="panel">
                <div class="panel-body text-center">
                  <img
                    alt="Profile Picture"
                    src="img/profile-photos/5.png"
                    class="img-lg img-circle mar-btm"
                    style="margin-left: 30%"
                  />
                  <p
                    class="text-semibold mar-no text-main"
                    style="font-size: 1.2em;"
                  >
                    {{ name }}
                  </p>
                  <p class="text-muted">ADMIN</p>
                  <div class="mar-top">
                    <button class="btn btn-mint">Follow</button>
                    <button class="btn btn-mint">Message</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-9">
          <div class="panel panel-body">
            <div class="panel-heading mar-btm">
              <h3
                class="pad-btm bord-btm text-thin"
                style="font-size: 20px;/* padding: 15px 0px 0px 25px; */"
              >
                <i class="demo-pli-male icon-lg"></i>
                Web Users
              </h3>
            </div>
            <div class="table-responsive">
              <table class="table table-vcenter mar-top">
                <thead>
                  <tr>
                    <!-- <th class="min-w-td">#</th> -->
                    <th class="min-w-td">Name</th>
                    <th>Company</th>
                    <th>Business Unit</th>
                    <th>Department</th>
                    <th>Section</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="6" style="text-align: center;">
                      No data available.
                    </td>
                  </tr>
                  <tr v-for="(data, index) in data.data" :key="index">
                    <!-- <td class="min-w-td">{{ index }}</td> -->
                    <td>
                      <img
                        src="img/profile-photos/1.png"
                        alt="Profile Picture"
                        class="img-circle img-sm"
                      />
                    </td>
                    <td>
                      <a href="javascript:;" class="btn-link">{{
                        data.name
                      }}</a>
                    </td>
                    <td>{{ data.company }}</td>
                    <td>
                      {{ data.business_unit }}
                    </td>
                    <td>
                      {{ data.section }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    Showing {{ data.from }} to <strong>{{ data.to }}</strong> of
                    <strong> {{ data.total }} entries </strong>
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
import Users from './Users.vue'
export default {
  data() {
    return {
      data: {
        data: [],
        current_page: null,
        to: null,
        from: null,
        total: null,
        per_page: null
      },
      name: null,
      date: this.getFormattedDateToday(),
      total_result: 0
    }
  },
  components: {
    Users
  },
  methods: {
    getResults(page = 1) {
      let url = `/setup/users/getResults/?page=`

      axios.get(url + page).then(({ data }) => {
        this.data = data
        this.total_result = data.total
      })
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
    this.getResults()
  }
}
</script>

<style lang="scss" scoped></style>
