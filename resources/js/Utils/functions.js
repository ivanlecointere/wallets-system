import dayjs from 'dayjs';

const formatDate = (date, format = 'MMMM D YYYY, h:mm:ss a') => {
    if (!date) {
        return '';
    }

    return dayjs(date).format(format);
};

export {
    formatDate,
};
