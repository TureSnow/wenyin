//
//  main.cpp
//  ArrangeShift
//
//  Created by mov on 2018/4/23.
//  Copyright © 2018 wjp. All rights reserved.

//人、时间、班、选班下标都从0开始；
#include <iostream>
#include <map>
#include <queue>
#include <set>
#include <ctime>
#include <fstream>
#include <sstream>
#include <climits>
#include <cstdlib>
#include <cstring>
#include <memory.h>

using namespace std;

string file="/Users/wanjunpeng/Desktop/文印网站/info.txt";
string file2="/Users/wanjunpeng/Desktop/文印网站/info2.txt";
string outfile="/Users/wanjunpeng/Desktop/文印网站/result.txt";

vector<string> id2num; //"id"与num的映射；

map<int, set<int> > bipartite;// =generate_staffWithSelect(); //选班二分图
map<int, set<int> > select_bi;  //排班过程中使用每个select连接相应的班；
//map<int, set<int> > timeSelectNum;//每个时间段已经选的人数，其实也就是id数；

//员工数 与 当班时间数
int time_nums=28;
int staff_nums=45;
//根据这个数组生成每个班；
vector<int> shift_max;
//每个班的持续时间
vector<int> shift_time;
vector<int> shift_mask;
vector<int> shift_thes;

void read_shift_info(string file){
    ifstream fin(file.c_str());
    string line,id;
    int value;
    
    fin>>line;
    fin>>time_nums;
    
    vector<int> a1(time_nums,0);
    vector<int> a2(time_nums,0);
    
    fin>>line;
    cout<<line<<endl;
    
    while(fin>>id){
        if(id=="nums")
            break;
        int idd = atoi(id.c_str())-1;
        if(idd>=time_nums){
            fin>>value;
            continue;
        }
        
        cout<<idd<<" ";
        fin>>value;
        cout<<value<<endl;
        a1[idd] = value;
    }
    
    shift_time = a1;
    
    staff_nums = 0;
    while(fin>>id){
        int idd = atoi(id.c_str())-1;
        cout<<idd<<" ";
        if(idd>=time_nums){
            fin>>value;
            continue;
        }
        
        fin>>value;
        cout<<value;
        staff_nums+=value;
        a2[idd] = value;

    }
    cout<<staff_nums<<endl;
    shift_max =a2;
    shift_mask= a2;
    shift_thes = a2;
}

//包括构建二分图
void read_txt(string file){
    ifstream fin(file.c_str());
    string line;
    int idx=0;
    while(getline(fin, line)){
        istringstream lin(line.c_str());
        string id; lin>>id; //读入id
        id2num.push_back(id); // 构建映射关系
        
        set<int> times;
        int shift_time;  //构建选班图
        while(lin>>shift_time){
            //           timeSelectNum[shift_time].insert(idx); //这里是时间和人对应图
            times.insert(shift_time);
        }
        bipartite[idx]=times; //这里是人和时间对应图
        idx++;
    }
    //idx为此时员工数
    staff_nums = idx;
    fin.close();
}


int * staffHaveTime = (int *)malloc(sizeof(int)*staff_nums);
//主函数里会先memset， 初始化为0

map<int, set<int> > time2shift;   //每个时间对应的排班
map<int, set<int> > staff2select; //每个人对应选班的索引

//广搜，使用匈牙利算法；
//匈牙利算法中，时间的分配要改一下
bool dfs(int u, int* match,int* match_2, int * shift, int * select,int* checked){
    for(set<int>::iterator it=select_bi[u].begin(); it!=select_bi[u].end();it++){
        int v= *it; //遍历u的每一个邻接点
        if(!checked[v]){
            checked[v]=true;
            //如果未匹配且无同一个人的选班匹配本时间段
            if( match_2[v]==-1 || dfs(match_2[v], match,match_2,shift,select,checked) ){
                if(match_2[v]!=-1){ //如果原本匹配了时间，原来人先减去这个时间
                    staffHaveTime[select[match_2[v]]]-= shift_time[shift[v]];
                    //match_2[v]=-1;
                }
                match[u]= v;
                match_2[v]=u;
                //此人加上时间
                staffHaveTime[select[u]]+= shift_time[shift[v]];
                //调整staffHaveTime
                return true;
            }
        }
    }
    return false;
}
/*
 主函数在此！！！！！！！！！
 
 */

int main(){
    read_txt(file);
    read_shift_info(file2);
    
    memset(staffHaveTime, 0, staff_nums);
    
    time_t t_start= clock();
    int shift_nums=0;       //班的总个数
    for(int i=0; i< time_nums; i++){
        shift_nums+= shift_max[i];
    }
    
    cout<<shift_nums<<endl;
    
    int shift[shift_nums];   //每个班对应的时间
    
    for(int i=0,t=0;i<time_nums;){
        time2shift[i].insert(t);  //时间到班的快速索引
        shift[t++]= i;  //对应时间
        shift_max[i]--; //将最大班次减少
        if(shift_max[i]<=0) i++;
    }
    
    int select_nums=0;     // 选班的总个数；
    for(int i=0; i<bipartite.size(); i++){
        select_nums+= bipartite[i].size();
    }
    
    int select[select_nums];       //每个选班对应的人
    
    //构建选班和人的对应关系 构建使用的二分图的邻接矩阵
    for(int i=0, t=0; i<staff_nums ; i++){
        for(set<int>::iterator it=bipartite[i].begin(); it!=bipartite[i].end(); it++){
            select[t]= i;           //从班到人的索引->数组
            staff2select[i].insert(t);  //从人到班的快速索引->map  //*it 人对应的时间
            for(set<int>::iterator it2=time2shift[*it].begin(); it2 !=time2shift[*it].end(); it2++)
                select_bi[t].insert(*it2);  //每个拆分的班次与可选的时间段相匹配
            t++;
        }
    }
    
    for(int i=0; i<select_nums; i++)
        cout<<select[i]<<" ";
    cout<<endl;
    
    int match[select_nums]; //选班--> 班
    int match_2[shift_nums]; //班--->选班
    memset(match, -1, sizeof(match));//将其初始化为 -1
    memset(match_2, -1, sizeof(match_2));

    int checked[shift_nums]; //每一轮中，检查班是否被选；
    
    //改进后的匈牙利算法主函数 //注意，这个函数中，select2staff 这个索引会被改动
    int match_num=0;
    int iter=0; // 定义最大迭代次数
    
    int ind_for_adjust=0;
    
    
    //开始迭代的阈值：班次数减去空班的人数
    int threshold= 0;

    for(int i = 0; i < bipartite.size(); i++){
        for(set<int>::iterator it = bipartite[i].begin(); it!=bipartite[i].end(); it++){
            if(shift_thes[*it] > 0)
                shift_thes[*it] = shift_thes[*it] - 1;
        }
    }
    
    
    for(int i=0; i<time_nums; i++){
        threshold += (shift_mask[i] - shift_thes[i]);
    }
    cout<<"被选班次数"<<endl;
    cout<<threshold<<endl;
    
    while( iter++<100000){
        //if里面的代码为了防止时间不均匀
        if(match_num >= threshold){
            //维护一个每个人当班时间的数组，简单的搜索，如果此班被一个当班更多的人当，则将此班换人；
            if(match[ind_for_adjust]==-1) {//如果已经匹配，继续循环
                //遍历此select相连的所有shift
                for(set<int>::iterator it=select_bi[ind_for_adjust].begin();it!=select_bi[ind_for_adjust].end();it++){
                    //cout<<*it<<endl;
                    int timeForShift= shift_time[shift[*it]]; //这个班对应的时间长短
                    //如果交换后，时间分配会变得更均匀
                    int death=match_2[*it]; //可能会被换掉的选班
                    if(staffHaveTime[select[death]]-timeForShift> staffHaveTime[select[ind_for_adjust]])
                    {
                        staffHaveTime[select[death]]   -= timeForShift;
                        staffHaveTime[select[ind_for_adjust]] += timeForShift;
                        
                        match[death] = -1;
                        match[ind_for_adjust]=  *it;
                        match_2[*it]= ind_for_adjust;
                        break;
                        //否则，有可能先与前换，再与后换，而与前换完后，已经match了；
                    }
                }
            }//end of if 判断是不是-1
            
            ind_for_adjust= (ind_for_adjust+1) % select_nums;
            continue;
        }//end of if
        
        int i=ind_for_adjust;
        
        if(match[i]!=-1){  //这个选班已经被匹配
            ind_for_adjust=(ind_for_adjust+1)%select_nums;
            continue;
        }
        
        memset(checked, 0, sizeof(checked));
        if(dfs(i, match, match_2, shift,select,checked))
            match_num++;
        
        ind_for_adjust=(ind_for_adjust+1)%select_nums;
    }
    //排班已经完成；
    cout<<"子选班个数"<<select_nums<<endl;
    cout<<"子排班个数"<<shift_nums<<endl;
    
    // 匹配结果
    cout<<"match:"<<endl;
    for(int i=0; i<select_nums; i++)
        cout<<match[i]<<" ";
    cout<<endl;
    
    cout<<"match_2:"<<endl;
    for(int i=0; i<shift_nums; i++)
        cout<<match_2[i]<<" ";
    cout<<endl;
    // 得到每个班的排班结果
    map<int, vector<int> > result;
    map<int, int> total_time;
    for(int i=0; i<staff_nums; i++)
        total_time.insert(pair<int,int>(i,0));
    
    for(int i=0; i<shift_nums; i++)
        if(match_2[i]!=-1){
            int t=shift[i];
            int s=select[match_2[i]];
            result[t].push_back(s);
            total_time[s]+= shift_time[t];
        }
    //输出每个班都有谁；
    for(int i=0; i<time_nums;i++){
        cout<<"第"<<i<<"班：  ";
        for(int j=0; j<result[i].size();j++){
            if(result[i][j]<id2num.size())
                cout<<id2num[result[i][j]]<<" ";
            else
                cout<<result[i][j]<<" ";
        }
        cout<<endl;
    }
    //统计每个人当班的时间：
    for(int i=0; i<staff_nums; i++){
        if(i<id2num.size())
            cout<<id2num[i]<<": "<<total_time[i]<<endl;
        else
            cout<<"第"<<i<<"个人"<<": "<<total_time[i]<<endl;
    }
    
    
    ofstream out(outfile.c_str());
    for(int i=0; i<time_nums;i++){
        out<<i<<" ";
        for(int j=0; j<result[i].size();j++){
            if(result[i][j]<id2num.size())
                out<<id2num[result[i][j]]<<" ";
        }
        out<<endl;
    }
    // out<<" "<<endl; //为防止没有选班信息，输出一个回车,但好像并没用
    // 没人选班时，不影响结果，这个bug不需要修改
    out.close();
    
    time_t t_end= clock();
    cout<<"花费时间"<<((double)t_end-t_start)/CLOCKS_PER_SEC<<endl;
    // int * staffHaveTime = (int *)malloc(sizeof(int)*staff_nums);
    free(staffHaveTime);
    //linux 可能多一点，但感觉1s钟之内也排得完;
    return 0;
    
}
