import { toolbar, reports } from "./rbac-consts";

const rules = {
  ROLE_ADMIN: {
    static: [
      toolbar.ADMIN,
      reports.ADMIN
    ],
    dynamic: {}
  },

  ROLE_USER: {
    static: [
      toolbar.USER,
    ],
    dynamic: {}
  },
};

export default rules;