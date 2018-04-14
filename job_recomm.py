import sys
useridpassed=0
if(len(sys.argv[1])>1):
	useridpassed=sys.argv[1]

#print useridpassed

import MySQLdb

import matplotlib.pyplot as plt
import seaborn as sns
import pandas as pd
import numpy as np
import ast 
from scipy import stats
from ast import literal_eval
from sklearn.feature_extraction.text import TfidfVectorizer, CountVectorizer
from sklearn.metrics.pairwise import linear_kernel, cosine_similarity
import warnings; warnings.simplefilter('ignore')

# Open database connection
connection = MySQLdb.connect(
    host='localhost', user='root',
    passwd='', db='onlinejobportal')

user = pd.read_sql('SELECT job_seeker_profile.user_account_id,job_seeker_profile.highest_degree,job_seeker_profile.highest_major,job_seeker_profile.year_of_exp FROM job_seeker_profile LEFT JOIN  user_account ON job_seeker_profile.user_account_id=user_account.id AND user_account.is_delete=0 AND user_account.is_active=1 AND user_account.is_approved=1', con=connection)
#print user

#print user.head(2)
user['highest_degree'] = user['highest_degree'].fillna('')
user['highest_major'] = user['highest_major'].fillna('')
user['year_of_exp'] = str(user['year_of_exp'].fillna(''))
user['highest_degree'] = user['highest_degree'] + user['highest_major'] + user['year_of_exp']
#print user['highest_degree']

tf = TfidfVectorizer(analyzer='word',ngram_range=(1, 2),min_df=0, stop_words='english')
tfidf_matrix = tf.fit_transform(user['highest_degree'])
#print tfidf_matrix.shape
cosine_sim = linear_kernel(tfidf_matrix, tfidf_matrix)
#print cosine_sim
user = user.reset_index()
#print user
userid = user['user_account_id']
indices = pd.Series(user.index, index=user['user_account_id'])

def get_recommendations_userwise(userid):
    idx = indices[userid]
    #print (idx)
    sim_scores = list(enumerate(cosine_sim[idx]))
    #print (sim_scores)
    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
    user_indices = [i[0] for i in sim_scores]
    #print (user_indices)
    return user_indices[0:11]

apps=pd.read_sql("SELECT job_post_activity.* FROM job_post_activity WHERE is_removed='0'  ", con=connection);

jobs = pd.read_sql("SELECT job_post.id, job_post.job_title, job_post.job_description FROM job_post ", con=connection)
#print apps
def get_job_id(usrid_list):
    jobs_userwise = apps['user_account_id'].isin(usrid_list) #
    #print "=========="
    #print jobs_userwise
    df1 = pd.DataFrame(data = apps[jobs_userwise], columns=['job_post_id'])
    joblist = df1['job_post_id'].tolist()
    Job_list = jobs['id'].isin(joblist) #[1083186, 516837, 507614, 754917, 686406, 1058896, 335132])
    #df_temp = pd.DataFrame(data = jobs[Job_list], columns=['id','job_title','job_description'])
    df_temp = pd.DataFrame(data = jobs[Job_list], columns=['id'])
    #df=df_temp.values
    x = df_temp.to_string(header=False,
                  index=False,
                  index_names=False).split('\n')
    vals = [','.join(ele.split()) for ele in x]
    return vals
    #return 1
useridpassed= int(useridpassed)
#print useridpassed
#print ("-----Recommendated Jobs for userId: 28------")
print (get_job_id(get_recommendations_userwise(useridpassed)))
#print "python ruiing"