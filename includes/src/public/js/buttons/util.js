const getURL = (svgUrl = '') => CL_SVGS_URL + svgUrl;

const findSvg = (svgId) => getURL(svgId + '.svg');
