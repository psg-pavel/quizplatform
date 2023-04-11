const zone = document.querySelector('.zone');

const str0 = document.querySelector('#str-0');
const str1 = document.querySelector('#str-1');
const str2 = document.querySelector('#str-2');
const str3 = document.querySelector('#str-3');
const str4 = document.querySelector('#str-4');
const str5 = document.querySelector('#str-5');

zone.ondragover = allowDrop;

function allowDrop(event){
	event.preventDefault();
}

str0.ondragstart = drag;
str1.ondragstart = drag;
str2.ondragstart = drag;
str3.ondragstart = drag;
str4.ondragstart = drag;
str5.ondragstart = drag;
function drag(event){
	event.dataTransfer.setData('id', event.target.id);
}
zone.ondrop = drop0;
function drop0(event){
	let itemid = event.dataTransfer.getData('id');
	event.target.before(document.getElementById(itemid));
}
function changegroups() {
	let fid = $( "li:eq(0)" ).attr( "id" );
	let id0 = Number(fid.slice(-1));
	fid = $( "li:eq(1)" ).attr( "id" );
	id1 = Number(fid.slice(-1));
	fid = $( "li:eq(2)" ).attr( "id" );
	id2 = Number(fid.slice(-1));
	fid = $( "li:eq(3)" ).attr( "id" );
	id3 = Number(fid.slice(-1));
	fid = $( "li:eq(4)" ).attr( "id" );
	id4 = Number(fid.slice(-1));
	fid = $( "li:eq(5)" ).attr( "id" );
	id5 = Number(fid.slice(-1));
	var groupsmassive = [id0, id1, id2, id3, id4, id5];
	document.cookie = 'groupsmassive=' + groupsmassive.toString();
	$("#groups_popup").hide();
}