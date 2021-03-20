import { Procedure } from "@app/interface/form/procedure";
import { PROCEDURE_TYPES } from "@app/variables";

export const getObjectFromArray = (array, value, pk = "id") =>
  array.find(obj => obj[pk] === value);

export const displayData = (obj, value, defaultValue = "") =>
  obj[value] || defaultValue;

export const adjustColor = (color, point = 1) => color.replace("alpha", point);

export const categoriesArrange = array => {
  const procedureTypes = array.reduce((arr, { procedure_type }) => {
    if (!arr.includes(procedure_type)) {
      arr.push(procedure_type);
    }
    return arr;
  }, []);

  return procedureTypes.reduce((arr, name) => {
    arr.push({
      name,
      array: array.filter(({ procedure_type }) => name === procedure_type)
    });
    return arr;
  }, []);
};

export const getAge = date => {
  const birthday = new Date(date);
  const ageDifMs = Date.now() - birthday.getTime();
  const ageDate = new Date(ageDifMs);
  return Math.abs(ageDate.getUTCFullYear() - 1970);
};

export const isSenior = date => getAge(date) >= 60;

/* PROCEDURE TREE HANDLING */

const sortProceduresByIndex = (a, b) => {
  return a.index < b.index ? -1 : 1;
};

const sortByProceduresByName = (a, b) => a.name.localeCompare(b.name);

const getUniqueCategory = procedures => {
  return procedures.reduce((arr, procedure) => {
    const checkExist = arr.find(
      obj => obj.category_id === procedure.category_id
    );
    if (!checkExist) {
      const { category, category_id, index } = procedure;
      arr.push({ category, category_id, index, array: [] });
    }
    return arr;
  }, []);
};

const subCategorizeProcedures = procedures => {
  const array = getUniqueCategory(procedures);

  return array.map(subCategory => {
    const subCategoryProcedures = procedures
      .filter(procedure => procedure.category_id === subCategory.category_id)
      .sort(sortByProceduresByName);
    subCategory.array = subCategoryProcedures;

    return subCategory;
  });
};

const filterByCategory = (procedures, category) =>
  procedures.filter(item => item.procedure_type === category);

export const categorizeProcedures = procedures => {
  const proceduresFlatten = procedures.map(procedure => {
    const { procedure_type_category: category } = procedure;

    return Object.assign(procedure, {
      procedure_type: category.procedure_type,
      category_id: category.id,
      category: category.name,
      index: category.index
    });
  });

  return Object.keys(PROCEDURE_TYPES).reduce((arr, category) => {
    const filteredProceduresArray = filterByCategory(
      proceduresFlatten,
      category
    );
    const array = subCategorizeProcedures(filteredProceduresArray).sort(
      sortProceduresByIndex
    );

    if (array.length) {
      arr.push({
        category,
        array
      });
    }
    return arr;
  }, []);
};

export const deleteAllCookies = () => {
  var cookies = document.cookie.split(";");

  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i];
    var eqPos = cookie.indexOf("=");
    var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
  }
};

export const debounceFunc = (cb, toRef, timeout = 500) => {
  clearTimeout(toRef);
  toRef = setTimeout(cb, timeout);

  return toRef;
};

export default {
  displayData,
  getObjectFromArray,
  adjustColor,
  getAge,
  isSenior,
  deleteAllCookies,
  debounceFunc
};
