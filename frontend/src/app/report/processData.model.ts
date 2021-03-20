import { COLORS_ARRAY } from '@app/variables';
import { adjustColor } from '@app/helper/function';

class ProcessData {
  count(data) {
    const labelsArray = [];

    const total = data.reduce((total, { count }) => {
      total += count;
      return total;
    }, 0);

    const dataArray = data.reduce((arr, obj) => {
      labelsArray.push(`${obj.name} (${obj.count})`);

      const float = (obj.count / total) * 100;
      arr.push(Math.round((float + 0.00001) * 100) / 100);
      return arr;
    }, []);

    const backgroundColor = data.reduce((arr, v, index) => {
      arr.push(adjustColor(COLORS_ARRAY[index]));
      return arr;
    }, []);

    return { labelsArray, dataArray, backgroundColor };
  }

  earnings(data) {
    const labelsArray = [];

    const totalDiscounted = data.reduce((total, { amount }) => {
      total += amount;
      return total;
    }, 0);

    const totalOriginal = data.reduce((total, { original }) => {
      total += original;
      return total;
    }, 0);

    const dataArray = data.reduce((arr, obj) => {
      labelsArray.push(`${obj.name} (${obj.count})`);
      arr.push(Math.round((obj.amount + 0.00001) * 100) / 100);
      return arr;
    }, []);

    const dataOriginal = data.reduce((arr, obj) => {
      arr.push(Math.round((obj.original + 0.00001) * 100) / 100);
      return arr;
    }, []);

    const amountColor = data.reduce((arr, v, index) => {
      arr.push(adjustColor(COLORS_ARRAY[index]));
      return arr;
    }, []);

    const originalColor = data.reduce((arr, v, index) => {
      arr.push(adjustColor(COLORS_ARRAY[index], 0.5));
      return arr;
    }, []);

    return {
      labelsArray,
      dataArray,
      dataOriginal,
      amountColor,
      originalColor,
      totalDiscounted,
      totalOriginal
    };
  }

  patients(data) {
    const newLength = data.new.length;

    const newRecords = data.new.reduce((total, { count }) => {
      if (count) {
        total += 1;
      }
      return total;
    }, 0);

    const newRecordsCount = data.new.reduce((total, { count }) => {
      total += count;
      return total;
    }, 0);

    const oldLength = data.old.length;

    const oldRecords = data.old.reduce((total, { count }) => {
      if (count) {
        total += 1;
      }
      return total;
    }, 0);

    const oldRecordsCount = data.old.reduce((total, { count }) => {
      total += count;
      return total;
    }, 0);

    return {
      newLength,
      newRecords,
      newRecordsCount,
      oldLength,
      oldRecords,
      oldRecordsCount
    };
  }
}

export default new ProcessData();
